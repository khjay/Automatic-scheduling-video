<link href="https://cdn.datatables.net/select/1.1.0/css/select.dataTables.min.css" rel="stylesheet" type="text/css">
<style>
  #playList tr > *:nth-child(1) {
    display: none;
  }
  .moveUp {
    margin-left: 5px;
  }
  .moveDown {
    margin-left: 5px;
  }
  .btn_upd {
    margin-left: 5px;
  }
  .btn_del {
    margin-left: 5px;
  }
</style>
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <h1 class="page-header">排程更新
          <a class="btn btn-primary pull-right" href="<?php echo base_url('Schedule');?>">回列表</a>
        </h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h1 class="panel-title">排程更新</h1>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <form id="schedules_form" method="post" action="<?php echo base_url('Schedule/add_confirm');?>" enctype="multipart/form-data">
                  <div class="form-group">
                    <label>標題</label>
                    <input id="title" name="title" type="text" class="form-control" value="<?php echo $schedule_data['main'][0]['title'];?>">
                  </div>
                  <div class="form-group">
                    <label>排程描述</label>
                    <textarea id="description" name="description" class="form-control" rows="4"><?php echo $schedule_data['main'][0]['description'];?></textarea>
                  </div>
                  <div class="form-group">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                          撥放清單
                          <div class="pull-right">
                            <div class="btn-group" role="group" aria-label="...">
                              <button type="button" class="btn btn-default btn-sm" id="btn_add" data-toggle="modal" data-target="#modal_add">加入</button>
                              <button type="button" class="btn btn-default btn-sm" id="btn_setting" data-toggle="modal" data-target="#modal_set">設定</button>
                            </div>
                          </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                          <table class="table table-bordered table-hover" id="playList">
                            <thead>
                              <tr>
                                <th>影片編號</th>
                                <th>影片名稱</th>
                                <th>影片長度</th>
                                <th>起始時間</th>
                                <th>結束時間</th>
                                <th>操作</th>
                            </thead>
                            <tbody>
                              <?php foreach($schedule_data['info'] as $row): ?>
                              <tr>
                                <td><?php echo $row['vid'];?></td>
                                <td><?php echo $row['title'];?></td>
                                <td><?php echo $row['video_length'];?></td>
                                <td><?php echo $row['startTime'];?></td>
                                <td><?php echo $row['endTime'];?></td>
                                <td>
                                    <button type='button' class='btn btn-default moveUp'><span class='glyphicon glyphicon-arrow-up' aria-hidden="true"></span>上移</button>
                                    <button type='button' class='btn btn-default moveDown'><span class='glyphicon glyphicon-arrow-down' aria-hidden="true"></span>下移</button>
                                    <button type="button" class="btn btn-default btn_upd"><span class='glyphicon glyphicon-edit'>修改起始時間</button>
                                    <button type="button" class="btn btn-default btn_del"><span class="glyphicon glyphicon-trash">刪除</button>
                                </td>
                              </tr>
                              <?php endforeach ?>
                            </tbody>
                          </table>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                  </div>
                  <br/>
                  <input type="hidden" id="startDate" value="<?php echo $schedule_data['main'][0]['startDate'];?>">
                  <input type="hidden" id="startTime" value="<?php echo $schedule_data['main'][0]['startTime'];?>">
                  <button type="submit" class="btn btn-primary btn-lg" id="schedule_add_confirm" style="margin-right: 25px">送出</button>
                  <button type="reset" class="btn btn-success btn-lg">回復原始設定</button>
                </form>
              </div>
            </div>
          </div>
        </div> 
      </div> 
    </div>  
  </div>
</div>
<!-- VideoList -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="modal_add_lbl">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_add_lbl">影像清單</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <div class="panel panel-default">
              <!-- Default panel contents -->
              <div class="panel-heading">影像清單</div>
              <div class="panel-body">
                <table id="videoTable" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>影片編號</th>
                      <th>影片名稱</th>
                      <th>影片長度</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($video_data as $video_item): ?>
                    <tr>
                      <td><?php echo $video_item['id']; ?></th>
                      <td><?php echo $video_item['title']; ?></td>
                      <td><?php echo $video_item['video_length']; ?></td>
                    </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="modal_add_confirm">新增</button>
      </div>
    </div>
  </div>
</div>

<!-- VideoUpdate -->
<div class="modal fade" id="modal_upd" tabindex="-1" role="dialog" aria-labelledby="modal_upd_lbl">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_upd_lbl">更新影片</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label for="update_title" class="col-sm-3 control-label">影片名稱</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="update_title" readonly>
              <input type="hidden" id="update_video_id" value="">
            </div>
          </div>
          <div class="form-group">
            <label for="update_video_length" class="col-sm-3 control-label">影片長度</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="update_video_length" readonly>
            </div>
          </div>
          <div class="form-group">
            <label for="update_start_time" class="col-sm-3 control-label">起始時間</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="update_start_time">
            </div>
          </div>
          <div class="form-group">
            <label for="update_end_time" class="col-sm-3 control-label">結束時間</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="update_end_time" readonly>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="modal_update_confirm">更新</button>
      </div>
    </div>
  </div>
</div>
<!-- VideoSetting -->
<div class="modal fade" id="modal_set" tabindex="-1" role="dialog" aria-labelledby="setModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">系統設定</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label for="videoOffset" class="col-sm-3 control-label">影像間隔</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="videoOffset">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="btn_systemConfirm">保存</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/select/1.1.0/js/dataTables.select.min.js"></script>
<script src="https://rawgit.com/digitalBush/jquery.maskedinput/1.4.1/dist/jquery.maskedinput.min.js"></script>
<script src="<?php echo base_url('public/dist/js/schedule.js');?>"></script>
<script>
  $(function() {
    $(document).on('click', '#schedule_add_confirm', function(e) {
      e.preventDefault();
      if(!$.trim($("#title").val())) {
        sweetAlert("糟糕...", "您似乎未輸入排程標題", "error");
        return false;
      }
      else if(!$("#playList tbody tr").length) {
        sweetAlert("糟糕...", "您的排程還未加入影片", "error");
        return false;
      }
      else {
        var tableRow = [];
        $.each($("#playList tbody tr"), function(key, value) {
          var tmpRow = {'vid': $(this).children()[0].innerHTML, 'startTime': $(this).children()[3].innerHTML, 'endTime': $(this).children()[4].innerHTML};
          tableRow.push(tmpRow);
        });
        var title = $.trim($("#title").val());
        var description = $.trim($("#description").val());
        var startDate = $.trim($("#startDate").val());
        var startTime = $.trim($("#startTime").val());
        $.ajax({
          url: "<?php echo base_url();?>Schedule/update_confirm/<?php echo $schedule_data['main'][0]['id'];?>",
          type: 'POST',
          data: {title: title, description: description, startDate: startDate, startTime: startTime, tableRow: tableRow},
          dataType: 'text',
          success: function(msg) {
            console.log(msg);
            var response;
            try {
              response = JSON.parse(msg);
            }
            catch(e) {
              swal("糟糕...", "出現不可預期的錯誤: " + e, "error");
            }
            $("#schedule_add_confirm").text("送出");
            $("#schedule_add_confirm").prop('disabled', false);
            if(response[0] == 0) {
              swal({
                title: "更新成功",
                type: "success"
              },function(){
                location.href="<?php echo base_url('Schedule');?>";
              });
            }
          },
          beforeSend: function() {
            $("#schedule_add_confirm").text("處理中...");
            $("#schedule_add_confirm").prop('disabled', true);
          }
        });
      }
    });

    $(document).on('click', 'button[type=reset]', function() {
      location.reload();
    })
  });
</script>
