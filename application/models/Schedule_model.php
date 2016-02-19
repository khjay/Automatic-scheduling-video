<?php
defined("BASEPATH") OR exit("No direct script access allowed");
date_default_timezone_set('Asia/Taipei');
class Schedule_model extends CI_Model {

  function __construct() {
    parent::__construct();
    $ci = get_instance();
    $ci->load->helper('custom_helper');
  }
  
  public function get_records($restrict="") {
    //$sql = "select *, TIMESTAMPDIFF(SECOND, sub.startTime, sub.endTime) as s_diff from (select id, title, startDate, concat(startDate, ' ', min(startTime)) as startTime, concat(startDate, ' ', max(endTime)) as endTime from schedules_info, schedules where id = sid group by sid) as sub";
    $this->db->select('id, title, startDate, startTime, endTime, duration')->from('schedules');
    $query = $this->db->get();
    if($query -> num_rows() > 0)
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
    $query_result = $this->db->insert('schedules', $data);
    $sid = $this->db->insert_id();
    $tmp_cache = $this->db->last_query();
    $data = array();
    foreach($tableRows as $row) {
      $this->db->select('file_name')->from('videos')->where('id', $row['vid']);
      $query = $this->db->get()->result_array();
      $fileName = $query[0]['file_name'];
      list($y, $m, $d) = explode("-", $startDate);
      list($h, $mm, $s) = explode(":", $row['startTime']);
      $data[] = array('sid'=>$sid, 'vid'=>$row['vid'], 'startTime'=>$row['startTime'], 'endTime'=>$row['endTime']);
    }
    $tmp_cache = $this->db->last_query();
    $this->db->insert_batch('schedules_info', $data);
  }

  public function get_record_by_id($id) {
    $this->db->select('id, title, description, startDate, startTime')->from('schedules')->where('id', $id);
    $query = $this->db->get();
    if($query -> num_rows() > 0)
      $main = $query->result_array();
    else
      $main = array();
    $tmp_cache = $this->db->last_query();
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
    $data = array(
      'title' => $title,
      'description' => $description,
      'duration'  => $duration
    );
    if($startTime == "-" && $startDate == "-")
      $settingJob = false;
    else
      $settingJob = true;

    $this->db->where('id', $id);
    $this->db->update('schedules', $data); 
    $cache = $this->db->last_query();

    // if playTime was setted, then delete at job
    if($settingJob) {  
      $this->db->select("jobID")->from('schedules_info');
      $this->db->where('sid', $id);
      $jids = $this->db->get()->result_array();
      foreach($jids as $jid)
        rmJob($jid['jobID']);
    }

    $this->db->where('sid', $id);
    $this->db->delete('schedules_info');
    $cache = $this->db->last_query();
    $data = array();
    foreach($tableRows as $row) {
      $this->db->select('file_name')->from('videos')->where('id', $row['vid']);
      $query = $this->db->get()->result_array();
      $fileName = $query[0]['file_name'];
      list($h, $mm, $s) = explode(":", $row['startTime']);
      if($settingJob) {
        list($y, $m,  $d) = explode("-", $startDate);
        $scheduleStartTime = substr($startTime, 0, -3);
        $videoStartTime = substr($row['startTime'], 0, -3);
        $totalStart = totalStart($scheduleStartTime, $videoStartTime);
        $jid = setJob($fileName, "{$d}.{$m}.{$y}", $totalStart);
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
      $this->db->select('file_name, startTime, jobID, vid')->from('schedules_info');
      $this->db->join('videos', 'videos.id = schedules_info.vid');
      $this->db->where('sid', $schedules_item['id']);
      $query = $this->db->get();
      $info_datas = $query->result_array();
      list($y, $m, $d) = explode('-', $schedules_item['startDate']);
      $scheduleStartTime = substr($schedules_item['startTime'], 0, -3);
      foreach($info_datas as $row) {
        $videoStartTime = substr($row['startTime'], 0, -3);
        $totalStart = totalStart($scheduleStartTime, $videoStartTime);
        rmJob($row['jobID']);
        $jid = setJob($row['file_name'], "{$d}.{$m}.{$y}", $totalStart);
        $this->db->where(array('sid' => $schedules_item['id'], 'vid' => $row['vid']));
        $this->db->update('schedules_info', array('jobID' => $jid));
        $cache = $this->db->last_query();
      }
    }
    $this->db->update_batch('schedules', $schedules_main, 'id'); 
    //$this->db->update_batch('schedules_info', $schedules_info, array('sid, vid')); 
  }
}
