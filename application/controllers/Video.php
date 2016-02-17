<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends CI_Controller {

  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   *    http://example.com/index.php/welcome
   *  - or -
   *    http://example.com/index.php/welcome/index
   *  - or -
   * Since this controller is set as the default controller in
   * config/routes.php, it's displayed at http://example.com/
   *
   * So any other public methods not prefixed with an underscore will
   * map to /index.php/welcome/<method_name>
   * @see https://codeigniter.com/user_guide/general/urls.html
   */
  function __construct() {
    parent::__construct();
    $this->load->model('Video_model');
    $this->load->helper('custom_helper');
  }

  public function index()
  {
    $this->load->view('common/head');
    $this->load->view('common/menu');
    $restrict = $this->input->post("search_title", TRUE);
    $video_data = $this->Video_model->get_records($restrict);
    $data = array(
      'video_data'=> count($video_data) == 0 ?  array() : $video_data ,
    );
    $this->load->view('videos/video_list', $data);
    $this->load->view('common/footer');
  }

  public function add() {
    $this->load->view('common/head');
    $this->load->view('common/menu');
    $this->load->view('videos/add');
    $this->load->view('common/footer');
  }

  public function add_confirm() {
    $status = file_upload();
    if($status[0] == 0) {
      $responsePattern = $this->Video_model->add_video($status[1][0], $status[1][1], $status[1][2]);
      if($responsePattern[0] == '0')
        echo json_encode(array('0', "上傳成功"));
      else
        echo json_encode(array('1', $responsePattern[1]));
    }
    else
      echo json_encode(array('1', $status[1]));
  }

  public function update($id) {
    $this->load->view('common/head');
    $this->load->view('common/menu');
    $video_data = $this->Video_model->get_record_by_id($id);
    $data = array(
      'video_data'=> count($video_data) == 0 ?  array() : $video_data ,
    );
    $this->load->view('videos/update', $data);
    $this->load->view('common/footer');
  }

  public function update_confirm($id) {
    $this->Video_model->update_video($id);
    $data = $this->Video_model->get_record_by_id($id);
    if(isset($_FILES["videoInput"])) {
      unlink('./uploads/'.$data[0]['file_name']);
      $status = file_upload();
      if($status[0] == 0) {
        $responsePattern = $this->Video_model->update_video_attachement($id, $status[1][0], $status[1][1], $status[1][2]);
        if($responsePattern[0] == '0') {
          echo json_encode(array('0', "更新成功"));
          $this->Video_model->update_video($id);
          $data = $this->Video_model->get_record_by_id($id);
        }
        else {
          echo json_encode(array('1', $responsePattern[1]));
          return ;
        }
      }
      else {
        echo json_encode(array('1', $responsePattern[1]));
        return ;
      }   
    }
    else {
      echo json_encode(array('0', "更新成功"));
      $this->Video_model->update_video($id);
      $data = $this->Video_model->get_record_by_id($id);
    }
  }

  public function remove($id) {
    $data = $this->Video_model->get_record_by_id($id);
    unlink('./uploads/'.$data[0]['file_name']);
    $this->Video_model->delete_video($id);
  }

}
