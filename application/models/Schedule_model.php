<?php
defined("BASEPATH") OR exit("No direct script access allowed");
date_default_timezone_set('Asia/Taipei');
class Schedule_model extends CI_Model {

  function __construct() {
    parent::__construct();
    $ci = get_instance();
    $ci->load->helper('custom_helper');
  }
  
  public function get_timeline_records() {
    $this->db->select("id, title, startDate, addtime(x.startTime, s.startTime) as mst, addtime(x.startTime, s.endTime) as mnt, duration, x.startTime as videoStartTime");
    $this->db->from('schedules as s');
    $this->db->join('(select sid, startTime from schedules_info order by startTime ) x', 'id=sid');
    $this->db->group_by('sid');
    $query = $this->db->get();
    if($query->num_rows() > 0)
      return $query->result_array();
    return array();
  }

  public function add_schedule() {
    $title = trim($this->input->post('title', TRUE));
    $description = trim($this->input->post('description', TRUE));
    $tableRows = $this->input->post('tableRow', TRUE);
    $duration = strtotime($tableRows[count($tableRows)-1]['endTime']) - strtotime($tableRows[0]['startTime']);
    $data = array(
      'title' => $title,
      'description' => $description,
      'duration' => $duration
    );
    // main data
    $query_result = $this->db->insert('schedules', $data);
    $sid = $this->db->insert_id();
    $cache = $this->db->last_query();
    $data = array();
    foreach($tableRows as $row) 
      $data[] = array('sid'=>$sid, 'vid'=>$row['vid'], 'startTime'=>$row['startTime'], 'endTime'=>$row['endTime']);
    // videos data
    $this->db->insert_batch('schedules_info', $data);
  }

  public function get_record_by_id($id) {
    $this->db->select('id, title, description, startDate, startTime')->from('schedules')->where('id', $id);
    $query = $this->db->get();
    if($query -> num_rows() > 0)
      $main = $query->result_array();
    else
      $main = array();
    $cache = $this->db->last_query();
    $this->db->select('vid, title, startTime, endTime, video_length')->from('schedules_info');
    $this->db->join('videos', 'videos.id = schedules_info.vid');
    $this->db->where('sid', $id);
    $this->db->order_by('startTime', 'asc');
    $query = $this->db->get();
    if($query -> num_rows() > 0)
      $info = $query->result_array();
    else
      $info = array();
    return array('main' => $main, 'info'  => $info);
  }

  public function update_schedule($id) {
    $title = trim($this->input->post('title', TRUE));
    $description = trim($this->input->post('description', TRUE));
    $startTime = trim($this->input->post('startTime', TRUE));
    $startDate = trim($this->input->post('startDate', TRUE));
    $tableRows = $this->input->post('tableRow', TRUE);
    $duration = strtotime($tableRows[count($tableRows)-1]['endTime']) - strtotime($tableRows[0]['startTime']);
    if($startTime == "-" && $startDate == "-")
      $settingJob = false;
    else
      $settingJob = true;

    $this->db->where('id', $id);
    $this->db->update('schedules', $data); 
    $cache = $this->db->last_query();

    // if playTime was set, then delete `at` job
    if($settingJob) {  
      $this->db->select("jobID")->from('schedules_info');
      $this->db->where('sid', $id);
      $jids = $this->db->get()->result_array();
      foreach($jids as $jid)
        rmJob($jid['jobID']);
    }
    
    // delete old schedule's videos
    $this->db->where('sid', $id);
    $this->db->delete('schedules_info');
    $cache = $this->db->last_query();
    $data = array();
    for($i=0; $i<count($tableRows); $i++) {
      $row = $tableRows[$i];
      $this->db->select('file_name')->from('videos')->where('id', $row['vid']);
      $query = $this->db->get()->result_array();
      $fileName = $query[0]['file_name'];
      if($settingJob) {
        list($y, $m,  $d) = explode("-", $startDate);
        $scheduleStartTime = substr($startTime, 0, -3);
        $videoStartTime = substr($row['startTime'], 0, -3);

        // schedule first video play time = schedule startTime + video startTime
        $totalStart = totalStart($scheduleStartTime, $videoStartTime);
        if($i < count($tableRows) -1)
          $loading = loadingDiff($row['endTime'], $tableRows[$i+1]['startTime']);
        else
          $loading = "00:00:00";
        $jid = setJob($fileName, "{$d}.{$m}.{$y}", $totalStart, $loading);
      }
      else
        $jid = -1;
      $data[] = array('sid'=>$id, 'vid'=>$row['vid'], 'startTime'=>$row['startTime'], 'endTime'=>$row['endTime'], 'jobID'=>$jid);
    }
    $this->db->insert_batch('schedules_info', $data);
    echo json_encode(array(0, 'success'));
  }

  public function remove_schedule($id) {
    $this->db->select("jobID")->from('schedules_info');
    $this->db->where('sid', $id);
    $jids = $this->db->get()->result_array();
    foreach($jids as $jid)
      rmJob($jid['jobID']);
    $tmp_cache = $this->db->last_query();
    $this->db->where('id', $id);
    $this->db->delete('schedules');
    echo $this->db->affected_rows();
  }

  public function update_timeline() {
    $schedules_main = $this->input->post('datas', true);
    $schedules_info = array();
    foreach($schedules_main as $schedules_item) {
      $this->db->select('file_name, startTime, endTime, jobID, vid')->from('schedules_info');
      $this->db->join('videos', 'videos.id = schedules_info.vid');
      $this->db->where('sid', $schedules_item['id']);
      $query = $this->db->get();
      $info_datas = $query->result_array();
      list($y, $m, $d) = explode('-', $schedules_item['startDate']);
      $scheduleStartTime = substr($schedules_item['startTime'], 0, -3);
      for($i=0; $i<count($info_datas); $i++) {
        $row = $info_datas[$i];
        $videoStartTime = substr($row['startTime'], 0, -3);
        $totalStart = totalStart($scheduleStartTime, $videoStartTime);
        rmJob($row['jobID']);
        if($i < count($info_datas)-1)
          $loading = loadingDiff($row['endTime'], $info_datas[$i+1]['startTime']);
        else
          $loading = "00:00:00";
        $jid = setJob($row['file_name'], "{$d}.{$m}.{$y}", $totalStart, $loading);
        $this->db->where(array('sid' => $schedules_item['id'], 'vid' => $row['vid']));
        $this->db->update('schedules_info', array('jobID' => $jid));
        $cache = $this->db->last_query();
      }
    }
    $this->db->update_batch('schedules', $schedules_main, 'id'); 
    //$this->db->update_batch('schedules_info', $schedules_info, array('sid, vid')); 
  }
}
