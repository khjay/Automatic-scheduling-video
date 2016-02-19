<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Taipei');
require_once("PHP-FFMPEG/vendor/autoload.php");
// ------------------------------------------------------------------------

if ( ! function_exists('format_second')) {
  function format_second($seconds) {
    $t = round($seconds);
    return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
  }
}

if ( ! function_exists('hmsToSecond') ) {
  function hmsToSecond($t) {
    list($H, $M, $S) = explode(":", $t);
    return ltrim($H, '0') * 3600 + ltrim($M, '0') * 60 + ltrim($S);
  }
}

if ( ! function_exists('ffmpeg_compute_video_duration')) {
  function ffmpeg_compute_video_duration($fileName) {
    $ffprobe = FFMpeg\FFProbe::create();
    return $ffprobe->streams($fileName)->videos()->first()->get('duration');
  }
}

if ( ! function_exists('file_upload') ) {
  function file_upload() {
    if(isset($_FILES["videoInput"]) && $_FILES["videoInput"]["error"]== UPLOAD_ERR_OK) {
      $UploadDirectory = './uploads/';

      //check if this is an ajax request
      if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        return array('1', '這不是正確的ajax請求!');
      }

      //Is file size is less than allowed size.
      if ($_FILES["videoInput"]["size"] > 104857600) {
        return array('1', "檔案超過上傳限制\n請確保檔案大小小於100MB");
      }

      //allowed file type Server side check
      switch(strtolower($_FILES['videoInput']['type'])) {
        case 'image/png': 
        case 'image/gif': 
        case 'image/jpeg': 
        case 'image/pjpeg':
        case 'text/plain':
        case 'text/html': //html file
        case 'application/x-zip-compressed':
        case 'application/pdf':
        case 'application/msword':
        case 'application/vnd.ms-excel':
        case 'video/mp4':
          break;
        default:
          return array('1', "我們並未支援該檔案格式\n請選擇其他檔案重新上傳");
      }
      $File_Name          = strtolower($_FILES['videoInput']['name']);
      $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
      $Random_Number      = uniqid(); //Random number to be added to name.
      $NewFileName        = $Random_Number.$File_Ext; //new file name

      if(move_uploaded_file($_FILES['videoInput']['tmp_name'], $UploadDirectory.$NewFileName )) {
        $VideoLength = format_second(ffmpeg_compute_video_duration($UploadDirectory.$NewFileName));
        return array(0, array($NewFileName, htmlspecialchars($_FILES['videoInput']['name']), $VideoLength));
      }
      else
        return array(1, '上傳失敗');
    }
    else
      return array(1, "上傳失敗\n請檢查'upload_max_filesize' 參數");
  }
}

if ( ! function_exists('formatLabel') ) {
  function formatLabel($startDate, $startTime, $endTime) {
    if($startDate == "-" && $startTime == "-" && $endTime == "-")
      return "<span class='label label-default'>未設定</span>";

    $now = date("Y-m-d H:i:s");
    $startTime  = $startDate . " " . $startTime;
    $endTime    = $startDate . " " . $endTime;
    if ($startTime > $now)
      return "<span class='label label-info'>等待播放</span>";
    else if ($startTime <= $now && $endTime >= $now)
      return "<span class='label label-success'>播放中</span>";
    else if ($endTime < $now)
      return "<span class='label label-warning'>播放完畢</span>";
  }
}

if (! function_exists('setJob') ) {
  function setJob($fileName, $startDate, $startTime) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:20001/unet/video_at?file_name={$fileName}&start_date={$startDate}&start_time={$startTime}");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
}

if (! function_exists('rmJob') ) {
  function rmJob($id) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:20001/unet/video_atrm?job_id={$id}");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_exec($ch);
    curl_close($ch);
  }
}

if (! function_exists('totalStart') ) {
  function totalStart($t1, $t2) {
    list($t1h, $t1m) = explode(":", $t1);
    list($t2h, $t2m) = explode(":", $t2);
    $total = ($t1h+$t2h) * 3600 + ($t1m+$t2m) * 60;
    if($total / 3600 < 10)
      $H = "0".floor($total/3600);
    else
      $H = floor($total/3600);
    $total %= 3600;
    if($total/60 < 10)
      $M = "0".floor($total/60);
    else
      $M = floor($total/60);
    return $H . ":" . $M;
  }
}
