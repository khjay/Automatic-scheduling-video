<?php
defined("BASEPATH") OR exit("No direct script access allowed");
date_default_timezone_set("Asia/Taipei");

class Video_model extends CI_Model {

  public function add_video($file_name, $origin_file_name, $video_length) {
    $title = trim($this->input->post("title", TRUE));
    $description = trim($this->input->post("description", TRUE));
    $created_at = $updated_at = date("Y-m-d H:i:s");
    $data = array(
      'title'=> $title,
      'description'=> $description,
      'file_name' => trim($file_name),
      'origin_file_name'  => trim($origin_file_name),
      'video_length' => $video_length,
      'created_at' => $created_at,
      'updated_at' => $updated_at
    );
    $query_result = $this->db->insert('videos', $data);
    if(!$query_result) {
      $this->error = $this->db->_error_message();
      $this->errorno = $this->db->_error_number();
      return array('1', "錯誤編號: {$this->errorno}\n錯誤訊息: {$this->error}");
    }
    else 
      return array('0', '');
  }

  public function get_records($restrict="") {
    $this->db->select('id, title, video_length, created_at, updated_at')->from('videos');
    if($restrict != "" && !is_null($restrict))
      $this->db->like('title', $restrict); 
    $this->db->order_by('created_at', 'desc');
    $query = $this->db->get();
    if($query -> num_rows() > 0)
      return $query->result_array();
    return array();
  }

  public function get_record_by_id($id) {
    $this->db->select('id, title, description, origin_file_name, file_name')->from('videos')->where('id', $id);
    $query = $this->db->get();
    if($query -> num_rows() > 0)
      return $query->result_array();
    return array();
  }

  public function update_video($id) {
    $data = array(
      'title' => trim($this->input->post('title', TRUE)),
      'description' => trim($this->input->post('description', TRUE)),
      'updated_at'  => date("Y-m-d H:i:s")
    );
    $this->db->where('id',$id);
    $this->db->update('videos',$data);
  }

  public function update_video_attachement($id, $file_name, $origin_file_name, $video_length) {
    $data = array(
      'file_name' => trim($file_name),
      'origin_file_name'  => trim($origin_file_name),
      'video_length'  => $video_length
    );
    $this->db->where('id', $id);
    $this->db->update('videos', $data);
    return array('0', '');
  }

  public function delete_video($id) {
    $this->db->where('id', $id);
    $this->db->delete('videos');
    echo $this->db->affected_rows();
  }
}
