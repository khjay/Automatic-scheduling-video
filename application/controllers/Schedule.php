<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Controller {

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
    $this->load->model('Schedule_model');
    $this->load->model('Video_model');
    $this->load->helper('custom_helper');
  }
  
  public function index() {
    $this->load->view('common/head');
    $this->load->view('common/menu');
    $restrict = $this->input->post("search_title", TRUE); $schedule_data = $this->Schedule_model->get_records($restrict);
    $schedule_data = $this->Schedule_model->get_records($restrict);
    $data = array(
      'schedule_data'=> count($schedule_data) == 0 ?  array() : $schedule_data ,
    );
    $this->load->view('schedules/schedule_list', $data);
    $this->load->view('common/footer');
  }

  public function add() {
    $this->load->view('common/head');
    $this->load->view('common/menu');
    $video_data = $this->Video_model->get_records();
    $data = array(
      'video_data'=> count($video_data) == 0 ?  array() : $video_data ,
    );
    $this->load->view('schedules/add', $data);
    $this->load->view('common/footer');
  }

  public function add_confirm() {
    $this->Schedule_model->add_schedule();
  }

  public function update($id) {
    $this->load->view('common/head');
    $this->load->view('common/menu');
    $schedule_data = $this->Schedule_model->get_record_by_id($id);
    $video_data = $this->Video_model->get_records();
    $data = array(
      'schedule_data'=> $schedule_data,
      'video_data'=> count($video_data) == 0 ?  array() : $video_data
    );
    $this->load->view('schedules/update', $data);
    $this->load->view('common/footer');   
  }

  public function update_confirm($id) {
    $this->Schedule_model->update_schedule($id);
  }

  public function remove($id) {
    echo $this->Schedule_model->remove_schedule($id);
  }

  public function test() {
    echo shell_exec("which at");
    echo 'haha';
  }

  public function timeline() {
    $this->load->view('common/head');
    $this->load->view('common/menu');
    $schedule_data = $this->Schedule_model->get_records();
    $data = array(
      'schedule_data'=> count($schedule_data) == 0 ?  array() : $schedule_data ,
    );
    $this->load->view('schedules/timeline', $data);
    $this->load->view('common/footer');
  }

  public function timeline_confirm() {
    $this->Schedule_model->update_timeline();
  }
}
