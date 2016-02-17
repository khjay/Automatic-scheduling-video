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
                    <label>播放時間</label>
                    <div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control" value="<?php echo $schedule_data['main'][0]['startDate'];?>"/>
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
                              <button type="button" class="btn btn-default btn-sm" id="btn_del">刪除</button>
                              <button type="button" class="btn btn-default btn-sm" id="btn_upd">更新</button>
                              <button type="button" class="btn btn-default btn-sm" id="btn_refresh">自動排列</button>
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
                                    <button type='button' class='btn btn-default moveUp'><span class='glyphicon glyphicon-arrow-up' aria-hidden='true'></span>上移</button>
                                    <button type='button' class='btn btn-default moveDown'><span class='glyphicon glyphicon-arrow-down' aria-hidden='true'></span>下移</button>
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
                  <input type="hidden" value="<?php echo $schedule_data['main'][0]['id'];?>">
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
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="modal_update_confirm">更新</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/select/1.1.0/js/dataTables.select.min.js"></script>
<script src="https://rawgit.com/digitalBush/jquery.maskedinput/1.4.1/dist/jquery.maskedinput.min.js"></script>
<script>
  $(function() {
    disabledRowMove();
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
        var tr2Append = "<tr><td>" + value[0] + "</td><td>" + value[1] + "</td><td>" + value[2] + "</td><td>00:00:00</td><td>" + value[2] + "</td><td>" + moveUp + moveDown + "</td></tr>";
        $("#playList tbody").append(tr2Append);
      });
      videoList.rows('.selected').deselect();
      
      $("#modal_add").modal('toggle');
      disabledRowMove();
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
        var selectedStartTime = $("#playList tbody tr.success td:nth-child(4)").text();
        var selectedEndTime = $("#playList tbody tr.success td:nth-child(5)").text();
        var selectedId = $("#playList tbody tr.success td:nth-child(1)").text();
        $("#modal_upd").find('.modal-title').text('更新影片: ' + selectedTitle);
        $("#update_title").val(selectedTitle);
        $("#update_video_id").val(selectedId);
        $("#update_video_length").val(selectedVideoLength);
        $("#update_start_time").val(selectedStartTime);
        $("#update_end_time").val(selectedEndTime);
      }
    });
    $("#update_start_time").focus( function (){
      $("#update_start_time").mask("99:99:99");
    });
    
    $("#start_time").focus(function() {
      $("#start_time").mask("")
    })

    $(document).on('change', '#update_start_time', function() {
      if($(this).val().length == 8) {
        var start_seconds = HmsToSecond($(this).val());
        var duration_seconds = HmsToSecond($("#update_video_length").val());
        $("#update_end_time").val(secondsToHms(start_seconds + duration_seconds));
      }
    });

    $(document).on('click', '#modal_update_confirm', function() {
      $("#playList tbody tr.success td:nth-child(4)").text($("#update_start_time").val());
      $("#playList tbody tr.success td:nth-child(5)").text($("#update_end_time").val());
      autoSorting();
      $("#modal_upd").modal('hide');
    });

    $(document).on('click', '.moveUp', function() {
      var row = $(this).parents('tr');
      row.insertBefore(row.prev());
      disabledRowMove();
    });

    $(document).on('click', '.moveDown', function() {
      var row = $(this).parents('tr');
      row.insertAfter(row.next());
      disabledRowMove();
    });

    $(document).on('click', '#btn_refresh', function() {
      autoSorting();
    });

    $(document).on('click', '#schedule_add_confirm', function(e) {
      e.preventDefault();
      autoSorting();
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
          $.each
          $.ajax({
            url: "<?php echo base_url();?>Schedule/update_confirm/<?php echo $schedule_data['main'][0]['id'];?>",
            type: 'POST',
            data: {title: title, description: description, datetime: datetime, tableRow: tableRow},
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
      }
    });
         
    $('#datetimepicker1').datetimepicker({
      viewMode: 'years',
      format: 'YYYY-MM-DD',
      minDate: new Date().yyyymmdd()
    });

    $(document).on('click', 'button[type=reset]', function() {
      var table_json = <?php echo json_encode($schedule_data['info']);?>;
      $.each($("#playList tbody tr"), function(key, value) {
        $(this).children()[0].innerHTML = table_json[key]['vid'];
        $(this).children()[1].innerHTML = table_json[key]['title'];
        $(this).children()[2].innerHTML = table_json[key]['video_length'];
        $(this).children()[3].innerHTML = table_json[key]['startTime'];
        $(this).children()[4].innerHTML = table_json[key]['endTime'];
      });
    })

    function secondsToHms(d) {
      d = Number(d);
      var h = Math.floor(d / 3600);
      var m = Math.floor(d % 3600 / 60);
      var s = Math.floor(d % 3600 % 60);
      return ( ((h < 10 ? "0" : "") + h) + ":" + ((m < 10 ? "0" : "") + m) + ":" + ((s < 10 ? "0" : "") + s) );
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

    function autoSorting() {
      var rows = $('#playList tbody tr').get();
      rows.sort(function(a, b) {
        var A = $(a).children('td').eq(4).text().toUpperCase();
        var B = $(b).children('td').eq(4).text().toUpperCase();
        if(A < B) {
          return -1;
        }

        if(A > B) {
          return 1;
        }
        return 0;
      });

      $.each(rows, function(index, row) {
        $('#playList').children('tbody').append(row);
      });
      disabledRowMove();
    }

    function disabledRowMove() {
      $('.moveUp').prop('disabled', false);
      $('.moveDown').prop('disabled', false);
      $('#playList tbody tr:first .moveUp').prop('disabled', true);
      $('#playList tbody tr:last .moveDown').prop('disabled', true);
    }
  });
</script>
