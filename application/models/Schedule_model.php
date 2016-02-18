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
    $sql = "select *, TIMESTAMPDIFF(SECOND, sub.startTime, sub.endTime) as s_diff from (select id, title, startDate, concat(startDate, ' ', min(startTime)) as startTime, concat(startDate, ' ', max(endTime)) as endTime from schedules_info, schedules where id = sid group by sid) as sub";
    $query = $this->db->query($sql);
    if($query -> num_rows() > 0)
       return $query->result_array();
    return array();
  }

  public function add_schedule() {
    $title = trim($this->input->post('title', TRUE));
    $description = trim($this->input->post('description', TRUE));
    $created_at = $updated_at = date("Y-m-d H:i:s");
    $startDate = trim($this->input->post('datetime', TRUE));
    $tableRows = $this->input->post('tableRow', TRUE);
    $data = array(
      'title' => $title,
      'description' => $description,
      'startDate'   => $startDate,
      'created_at'  => $created_at,
      'updated_at'  => $updated_at
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
      $jid = setJob($fileName, "{$d}.{$m}.{$y}", "{$h}:{$mm}");
      $data[] = array('sid'=>$sid, 'vid'=>$row['vid'], 'startTime'=>$row['startTime'], 'endTime'=>$row['endTime'], 'jobID'=>$jid);
    }
    $tmp_cache = $this->db->last_query();
    $this->db->insert_batch('schedules_info', $data);
  }

  public function get_record_by_id($id) {
    $this->db->select('id, title, description, startDate')->from('schedules')->where('id', $id);
    $query = $this->db->get();
    if($query -> num_rows() > 0)
      $main = $query->result_array();
    else
      $main = array();
    $tmp_cache = $this->db->last_query();
    $this->db->select('vid, title, startTime, endTime, video_length')->from('schedules_info');
    $this->db->join('videos', 'videos.id = schedules_info.vid');
    $this->db->where('sid', $id);
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
    $updated_at = date("Y-m-d H:i:s");
    $startDate = trim($this->input->post('datetime', TRUE));
    $tableRows = $this->input->post('tableRow', TRUE);
    $data = array(
      'title' => $title,
      'description' => $description,
      'startDate'   => $startDate,
      'updated_at'  => $updated_at
    );
    $this->db->where('id', $id);
    $this->db->update('schedules', $data); 
    $tmp_cache = $this->db->last_query();
    $this->db->select("jobID")->from('schedules_info');
    $this->db->where('sid', $id);
    $jids = $this->db->get()->result_array();
    foreach($jids as $jid)
      rmJob($jid['jobID']);
    $this->db->where('sid', $id);
    $this->db->delete('schedules_info');
    $tmp_cache = $this->db->last_query();
    $data = array();
    foreach($tableRows as $row) {
      $this->db->select('file_name')->from('videos')->where('id', $row['vid']);
      $query = $this->db->get()->result_array();
      $fileName = $query[0]['file_name'];
      list($y, $m, $d) = explode("-", $startDate);
      list($h, $mm, $s) = explode(":", $row['startTime']);
      $jid = setJob($fileName, "{$d}.{$m}.{$y}", "{$h}:{$mm}");
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
}
