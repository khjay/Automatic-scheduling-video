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
</style>
<div id="page-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <h1 class="page-header">排程新增
          <a class="btn btn-primary pull-right" href="<?php echo base_url('Schedule');?>">回列表</a>
        </h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h1 class="panel-title">排程新增</h1>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <form id="schedules_form" method="post" action="<?php echo base_url('Schedule/add_confirm');?>" enctype="multipart/form-data">
                  <div class="form-group">
                    <label>標題</label>
                    <input id="title" name="title" type="text" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>排程描述</label>
                    <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                  </div>
                  <div class="form-group">
                    <label>播放時間</label>
                    <div class='input-group date' id='datetimepicker1'>
                      <input type='text' class="form-control" />
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                          撥放清單
                          <div class="pull-right">
                            <div class="btn-group" role="group" aria-label="...">
                              <button type="button" class="btn btn-default btn-sm" id="btn_add" data-toggle="modal" data-target="#modal_add">加入</button>
                              <button type="button" class="btn btn-default btn-sm" id="btn_upd">編輯</button>
                              <button type="button" class="btn btn-default btn-sm" id="btn_del">刪除</button>
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
                            </tbody>
                          </table>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                  </div>
                  <br/>
                  <button type="submit" class="btn btn-primary btn-lg" id="schedule_add_confirm">送出</button>
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
        <h4 class="modal-title" id="modal_upd_lbl">編輯影片</h4>
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
<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/select/1.1.0/js/dataTables.select.min.js"></script>
<script src="https://rawgit.com/digitalBush/jquery.maskedinput/1.4.1/dist/jquery.maskedinput.min.js"></script>
<script>
  $(function() {
    var videoList = $('#videoTable').DataTable( {
      select: {
        style:    'multi',
      },
      "bLengthChange": false,
      // "iDisplayLength": 5,
      // "bInfo": false,
      "language": {
        "sProcessing":      "處理中...",
        "sLoadingRecords":  "載入中...",
        "sLengthMenu":      "顯示 _MENU_ 項結果",
        "sZeroRecords":     "沒有符合的結果",
        "sInfo":            "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
        "sInfoEmpty":       "顯示第 0 至 0 項結果，共 0 項",
        "sInfoFiltered":    "(從 _MAX_ 項結果中過濾)",
        "sInfoPostFix":     "",
        "sSearch":          "搜尋:",
        "sUrl":             "",
        "oPaginate": {
          "sFirst":    "第一頁",
          "sPrevious": "上一頁",
          "sNext":     "下一頁",
          "sLast":     "最後一頁"
        },
        "oAria": {
          "sSortAscending":  ": 升冪排列",
          "sSortDescending": ": 降冪排列"
        },
        select: {
          rows: "已選取 %d 列",
        }
      }
    });

    $(document).on('click', '#modal_add_confirm', function() {
      $.each(videoList.rows('.selected').data(), function(key, value) {
        var moveUp = "<button type='button' class='btn btn-default moveUp'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span>上移</button>";
        var moveDown = "<button type='button' class='btn btn-default moveDown'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span>下移</button>";
        var tr2Append = "<tr><td>" + value[0] + "</td><td>" + value[1] + "</td><td>" + value[2] + "</td><td></td><td></td><td>" + moveUp + moveDown + "</td></tr>";
        $("#playList tbody").append(tr2Append);
      });
      videoList.rows('.selected').deselect();
      
      $("#modal_add").modal('toggle');
      disabledRowMove();
      tableRefactor();
    });

    $(document).on('click', '#playList tbody tr', function() {
      if($("#playList tbody tr.success").length) {
        if($(this).hasClass('success'))
          $(this).removeClass('success');
        else {
          $("#playList tbody tr").removeClass('success');
          $(this).addClass('success');
        }
      }
      else
        $(this).addClass('success');
    });

    $(document).on('click', '#btn_del', function() {
      if($("#playList tbody tr.success").length == 0)
        swal("糟糕...", "您尚未選取任何列!!!", "warning");
      else {
        $("#playList tbody tr.success").remove();
        disabledRowMove();
      }
    });

    $(document).on('click', "#btn_upd", function() {
      if($("#playList tbody tr.success").length == 0)
        swal("糟糕...", "您尚未選取任何列!!!", "warning");
      else {
        $("#modal_upd").modal('toggle');
        var selectedTitle = $("#playList tbody tr.success td:nth-child(2)").text();
        var selectedVideoLength = $("#playList tbody tr.success td:nth-child(3)").text();
        var selectedStartTime = $("#playList tbody tr.success td:nth-child(4)").text().slice(0, -3).replace(":", "點 ") + "分";
        var selectedEndTime = $("#playList tbody tr.success td:nth-child(5)").text().replace(":", "點 ").replace(":", "分 ") + "秒"
        var selectedId = $("#playList tbody tr.success td:nth-child(1)").text();
        $("#modal_upd").find('.modal-title').text('編輯影片時間: ' + selectedTitle);
        $("#update_title").val(selectedTitle);
        $("#update_video_id").val(selectedId);
        $("#update_video_length").val(selectedVideoLength);
        $("#update_start_time").val(selectedStartTime);
        $("#update_end_time").val(selectedEndTime);
      }
    });

    $('#update_start_time').datetimepicker({
      format: 'HH點 mm分'
    });
    
    $("#update_start_time").on("dp.change", function (e) {
      var start_seconds = HmsToSecond($("#update_start_time").val().replace("點 ", ":").replace("分", ":00"));
      var duration_seconds = HmsToSecond($("#update_video_length").val());
      $("#update_end_time").val(secondsToHms(start_seconds + duration_seconds).replace(":", "點 ").replace(":", "分 ") + "秒");
    });

    $(document).on('click', '#modal_update_confirm', function() {
      var updateStartTime = $("#update_start_time").val().replace("點 ", ":").replace("分", ":00");
      var updateEndTime = $("#update_end_time").val().replace("點 ", ":").replace("分 ", ":").replace("秒", "");
      if(HmsToSecond(updateStartTime) <= HmsToSecond($("#playList tbody tr.success").prev().children()[4].innerHTML)) {
        tableRefactor($("#playList tbody tr.success").index()-1);
      }
      else {
        $("#playList tbody tr.success td:nth-child(4)").text(updateStartTime);
        $("#playList tbody tr.success td:nth-child(5)").text(updateStartTime);
        tableRefactor($("#playList tbody tr.success").index());
      }
      $("#modal_upd").modal('hide');
    });

    $(document).on('click', '.moveUp', function() {
      var row = $(this).parents('tr');
      row.children()[3].innerHTML = row.prev().children()[3].innerHTML;
      row.children()[4].innerHTML = secondsToHms(HmsToSecond(row.children()[3].innerHTML) + HmsToSecond(row.children()[2].innerHTML));
      row.insertBefore(row.prev());
      disabledRowMove();
      tableRefactor($(this).parents('tr').index());
    });

    $(document).on('click', '.moveDown', function() {
      var row = $(this).parents('tr');
      var rowPrev = row.prev();
      var rowNext = row.next();
      if(row.index() == 0) {
        rowNext.children()[3].innerHTML = "00:00:00";
        rowNext.children()[4].innerHTML = rowNext.children()[2].innerHTML;
      }
      else {
        rowNext.children()[3].innerHTML = leastMinutes(HmsToSecond(rowPrev.children()[4].innerHTML));
        rowNext.children()[4].innerHTML = secondsToHms(HmsToSecond(rowNext.children()[3].innerHTML) + HmsToSecond(rowNext.children()[2].innerHTML));
      }
      row.insertAfter(row.next());
      tableRefactor(row.index()-1);
      disabledRowMove();
    });

    $(document).on('click', '#schedule_add_confirm', function(e) {
      e.preventDefault();
      var tableRow = [];
      if(!$.trim($("#title").val())) {
        sweetAlert("糟糕...", "您似乎未輸入排程標題", "error");
        return false;
      }
      else if(!$("#datetimepicker1 input").val()) {
        sweetAlert("糟糕...", "您還沒選擇排程的播放時間", "error");
        return false;
      }
      else if(!$("#playList tbody tr").length) {
        sweetAlert("糟糕...", "您的排程還未加入影片", "error");
        return false;
      }
      else {
        var time_max = HmsToSecond($("#playList tbody tr:first").children()[4].innerHTML);
        var videoConflict = false;
        $.each($("#playList tbody tr"), function(key, value) {
          var tmpRow = {'vid': $(this).children()[0].innerHTML, 'startTime': $(this).children()[3].innerHTML, 'endTime': $(this).children()[4].innerHTML};
          tableRow.push(tmpRow);
          if(key == 0) return ;
          var startTime = HmsToSecond($(this).children()[3].innerHTML);
          var endTime = HmsToSecond($(this).children()[4].innerHTML);
          if(startTime < time_max) {
            sweetAlert("糟糕...", "您的影片：" + $(this).children()[1].innerHTML + " 似乎與其他影片的播放時間衝突", "error");
            videoConflict = true;
            return false;
          }
          else {
            time_max = endTime;
          }
        });
        // Everything is ok!!
        if(!videoConflict) {
          var title = $.trim($("#title").val());
          var description = $.trim($("#description").val());
          var datetime = $.trim($("#datetimepicker1").find('input').val());
          $.ajax({
            url: "<?php echo base_url('Schedule/add_confirm'); ?>",
            type: 'POST',
            data: {title: title, description: description, datetime: datetime, tableRow: tableRow},
            dataType: 'text',
            success: function(msg) {
              console.log(msg);
              $("#schedule_add_confirm").text("送出");
              $("#schedule_add_confirm").prop('disabled', false);
              swal({
                title: "新增成功",
                type: "success"
              },function(){
                location.href="<?php echo base_url('Schedule');?>";
              });
            },
            beforeSend: function() {
              $("#schedule_add_confirm").text("處理中...");
              $("#schedule_add_confirm").prop('disabled', true);
            },
            error: function(jqxhr, textStatus, errorThrown ) {
              console.log(jqxhr, textStatus, errorThrown);
            }
          });
        }
      }
    });

    $('#datetimepicker1').datetimepicker({
      viewMode: 'years',
      format: 'YYYY-MM-DD',
      minDate: new Date().yyyymmdd()
    });
    
    $('#videoOffset').datetimepicker({
      format: 'HH小時 mm分鐘'
    });

    $("#videoOffset").val("00小時 01分鐘")

    $(document).on('click', '#btn_systemConfirm', function() {
      tableRefactor();
      $("#modal_set").modal('hide');
    })
      
    function secondsToHms(d) {
      d = Number(d);
      var h = Math.floor(d / 3600);
      var m = Math.floor(d % 3600 / 60);
      var s = Math.floor(d % 3600 % 60);
      return ( ((h < 10 ? "0" : "") + h) + ":" + ((m < 10 ? "0" : "") + m) + ":" + ((s < 10 ? "0" : "") + s) );
    }

    function tableRefactor(n) {
      n = typeof n !== 'undefined' ? n : -1;
      var lastTR = "";
      $.each($("#playList tbody tr"), function(key, value) {
        if(key <= n) {
          lastTR = $(this);
          return ;
        }
        else {
          var videoStart = $(this).children()[3];
          var videoLength = $(this).children()[2];
          var videoEnd = $(this).children()[4];
          var videoOffset = HmsToSecond($("#videoOffset").val().replace("小時 ", ":").replace("分鐘", ":00"));
          if(key==0)
            videoStart.innerHTML = "00:00:00";
          else
            videoStart.innerHTML = leastMinutes(HmsToSecond(lastTR.children()[4].innerHTML) + videoOffset);
          videoEnd.innerHTML = secondsToHms(HmsToSecond(videoStart.innerHTML) + HmsToSecond(videoLength.innerHTML));
          lastTR = $(this); 
        }
      });
    }

    function leastMinutes(s) {
      return secondsToHms(Math.ceil(s/60)*60);
    }

    function HmsToSecond(d) {
      var a = d.split(':');
      if( (+a[0]) > 23 || (+a[1]) > 59 || (+a[2] > 59) ) {
        swal("糟糕...", "您的時間格式似乎不合規定!!!", "warning");
        $("#update_start_time").val('00:00:00');
        return 0;
      }
      else
       return (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
    }

    function disabledRowMove() {
      $('.moveUp').prop('disabled', false);
      $('.moveDown').prop('disabled', false);
      $('#playList tbody tr:first .moveUp').prop('disabled', true);
      $('#playList tbody tr:last .moveDown').prop('disabled', true);
    }
  });
</script>
