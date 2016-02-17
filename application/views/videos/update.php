<style>
  .btn-file {
    position: relative;
    overflow: hidden;
  }
  .btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    background: red;
    cursor: inherit;
    display: block;
  }
  input[readonly] {
    background-color: white !important;
    cursor: text !important;
  }
</style>
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <h1 class="page-header">
          <?php 
            if(count($video_data) == 0) 
              echo "查無此影像資料"; 
            else
              echo "影像更新";
          ?>
          <a class="btn btn-primary pull-right" href="<?php echo base_url('Video');?>">回列表</a>
        </h1>
      </div>
    </div>
    <?php if(count($video_data) != 0) { ?>
    <div class="row">
      <div class="col-lg-12 col-md-12">        
        <div class="panel panel-default">
          <div class="panel-heading">
            <h1 class="panel-title">影像更新</h1>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <form id="form_upload" method="post" action="<?php echo base_url('Video/update_confirm/' . $video_data[0]['id']);?>" enctype="multipart/form-data">
                  <div class="form-group">
                    <label>標題</label>
                    <input id="title" name="title" type="text" class="form-control" value="<?php echo $video_data[0]['title'];?>">
                  </div>
                  <div class="form-group">
                    <label>影片描述</label>
                    <textarea id="description" name="description" class="form-control" rows="4"><?php echo $video_data[0]['description'];?></textarea>
                  </div>
                  <div class="form-group">
                    <label>影像檔</label>
                    <div class="input-group">
                      <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                          瀏覽檔案&hellip; <input type="file" id="videoInput" name="videoInput">
                        </span>
                      </span>
                      <input type="text" class="form-control" value="<?php echo htmlspecialchars_decode($video_data[0]['origin_file_name']);?>" readonly>
                    </div>
                  </div>
                  <br/>
                  <div class="progress" id="progress-bar" style="display: none;">
                    <div class="progress-bar progress-bar-striped active" id="progress_step" role="progressbar" style="width: 45%">
                    </div>
                  </div>
                  <input type="hidden" name="id" value="<?php echo $video_data[0]['id'];?>">
                  <button type="submit" class="btn btn-primary btn-lg" style="margin-right: 25px">送出</button>
                  <button type="reset" class="btn btn-success btn-lg">回復原始設定</button>
                </form>
              </div>
            </div>
          </div>
        </div> 
      </div> 
    </div>
    <?php } ?>
  </div>
</div>
<script src="http://malsup.github.io/min/jquery.form.min.js"></script>
<script>
$(function() {
  $(document).on('change', '.btn-file :file', function() {
    var input = $(this),
    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', label);
  });
  
  $(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, label) {
      var input = $(this).parents('.input-group').find(':text');
      if( input.length )
        input.val(label);
      else
        if( label ) alert(label);
    });
  });
  var options = {
    beforeSubmit:  beforeSubmit,  // pre-submit callback 
    success:       afterSuccess,  // post-submit callback 
    uploadProgress: OnProgress,   // upload progress callback 
  };
  $('#form_upload').submit(function(e) { 
    e.preventDefault();
    
    $(this).ajaxSubmit(options);        
    // always return false to prevent standard browser submit and page navigation 
    return false;
  });

  function afterSuccess(responseText, statusText, xhr, $form) {
    var jsonResponse = "";
    try {
      jsonResponse = JSON.parse(responseText);
    }
    catch(e) {
      swal("糟糕...", "出現不可預期的錯誤: " + e, "error");
    }
    
    var statusCode = jsonResponse[0];
    if(statusCode == 0) {
      swal( {
        title: jsonResponse[1],
        type: "success",
        confirmButtonText: "OK",
        closeOnConfirm: false 
      },function(){
        window.location = "<?php echo base_url('Video');?>";
      });
    }
    else {
      sweetAlert("糟糕...", jsonResponse[1], "error");
      $('#progress_step').width('0%');
    }
    
  }

  function OnProgress(event, position, total, percentComplete) {
    if( $('#videoInput').val()) {
      $("#progress-bar").show();
      $('#progress_step').width(percentComplete + '%');
    }
  }

  function beforeSubmit(){
    //check whether browser fully supports all File API
    if (window.File && window.FileReader && window.FileList && window.Blob) {
      if( !$('#title').val()) {
        sweetAlert("糟糕...", "您似乎未輸入影像標題", "error");
        return false;
      }
      //check empty input filed
      if( $('#videoInput').val()) {
        var fsize = $('#videoInput')[0].files[0].size; //get file size
        var ftype = $('#videoInput')[0].files[0].type; // get file type
        //allow file types 
        switch(ftype) {
          case 'image/png': 
          case 'image/gif': 
          case 'image/jpeg': 
          case 'image/pjpeg':
          case 'text/plain':
          case 'text/html':
          case 'application/x-zip-compressed':
          case 'application/pdf':
          case 'application/msword':
          case 'application/vnd.ms-excel':
          case 'video/mp4':
            break;
          default:
            sweetAlert("糟糕...", "我們並未支援該檔案格式\n請選擇其他檔案重新上傳", "error");
            return false;
        }
        //Allowed file size is less than 100 MB (104857600)
        if(fsize>104857600) {
          sweetAlert("糟糕...", "檔案超過上傳限制\n請確保檔案大小小於100MB", "error");
          return false;
        }
      }
    }
    else {
      //Output error to older unsupported browsers that doesn't support HTML5 File API
      sweetAlert("糟糕...", "您的瀏覽器版本似乎太舊\n 將導致部分功能無法完整使用", "error");
      return false;
    }
  }
})
</script>
