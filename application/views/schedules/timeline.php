<style>
#scheduleList tr > *:nth-child(1),
#scheduleList tr > *:nth-child(7) {
  display: none;
}
</style>
<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">
        排程時間軸更新
        <a class="btn btn-primary pull-right" href="<?php echo base_url('Schedule');?>" style="color: #fff;text-decoration: none;">回列表</a>
      </h1>
    </div>
        <!-- /.col-lg-12 -->
  </div>
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="panel panel-info">
        <div class="panel-heading">
          排程列表
          <div class="pull-right">
            <div class="btn-group" role="group" aria-label="...">
              <button type="button" class="btn btn-default btn-sm" id="btn_upd">更新時間</button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-hover" id="scheduleList">
            <thead>
              <tr>
                <th></th>
                <th>排程名稱</th>
                <th>撥放日期</th>
                <th>開始時間</th>
                <th>結束時間</th>
                <th>排程狀態</th>
                <th>diff</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($schedule_data as $schedule_item): ?>
                <tr>
                  <td><?php echo $schedule_item['id']; ?></td>
                  <td><?php echo $schedule_item['title']; ?></td>
                  <td><?php echo $schedule_item['startDate']; ?></td>
                  <td><?php echo $schedule_item['startTime']; ?></td>
                  <td><?php echo $schedule_item['endTime']; ?></td>
                  <td><?php echo formatLabel($schedule_item['startDate'], $schedule_item['startTime'], $schedule_item['endTime']); ?></td>                  
                  <td><?php echo $schedule_item['duration'];?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
  <div class="col-md-12 col-lg-12">
    <button class="btn btn-lg btn-primary" id="btn_save_job"style="margin-right: 25px;">更新</button>
    <button class="btn btn-lg btn-success" type="reset">回復原始設定</button>
  </div>
</div>
</div>

<!-- ScheduleUpdate -->
<div class="modal fade" id="modal_upd" tabindex="-1" role="dialog" aria-labelledby="modal_upd_lbl">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_upd_lbl">編輯排程</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label for="update_title" class="col-sm-3 control-label">排程名稱</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="update_title" readonly>
              <input type="hidden" id="update_schedule_id" value="">
            </div>
          </div>
          <div class="form-group">
            <label for="update_start_date" class="col-sm-3 control-label">播放日期</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="update_start_date">
            </div>
          </div>
          <div class="form-group">
            <label for="update_start_time" class="col-sm-3 control-label">播放時間</label>
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
        <button type="button" class="btn btn-default" id="modal_cancel" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="modal_update_confirm">更新</button>
      </div>
    </div>
  </div>
</div>
<script>
$(function() {
  $(document).on('click', '#scheduleList tbody tr', function() {
    if($("#scheduleList tbody tr.success").length) {
      if($(this).hasClass('success'))
        $(this).removeClass('success');
      else {
        $("#scheduleList tbody tr").removeClass('success');
        $(this).addClass('success');      
      }
    }
    else
      $(this).addClass('success');
  });

  $(document).on('click', '#btn_upd', function() {
    if(!$("#scheduleList tbody tr.success").length) {
      swal("糟糕...", "您尚未選取任何列!!!", 'warning');
    }
    else {
      $('#update_start_time').data("DateTimePicker").clear();
      var selectedTitle = $("#scheduleList tbody tr.success td:nth-child(2)").text();
      var selectedStartDate = $("#scheduleList tbody tr.success td:nth-child(3)").text();
      var selectedStartTime = $("#scheduleList tbody tr.success td:nth-child(4)").text();
      var selectedEndTime = $("#scheduleList tbody tr.success td:nth-child(5)").text();
      var selectedId = $("#scheduleList tbody tr.success td:nth-child(1)").text();
      $("#modal_upd").find('.modal-title').text('編輯排程: ' + selectedTitle);
      $("#update_title").val(selectedTitle);
      $("#update_schedule_id").val(selectedId);
      if(selectedStartDate == "-") {
        $("#update_start_date").val('');
        $("#update_start_date").prop('placeholder', '請選擇日期');
        $("#update_start_date").data("DateTimePicker").clear();
      }
      else {
        $('#update_start_date').data("DateTimePicker").date(selectedStartDate);
        $("update_start_date").val(selectedStartDate);
      }
      if(selectedStartTime == "-") {
        $("#update_start_time").val(''); 
        $("#update_start_time").prop('placeholder', '請選擇播放時間');
      }
      else
        $("update_start_time").val(selectedStartTime.slice(0, -3).replace(":", "點 ") + "分");
      $("#update_end_time").val(selectedEndTime);
      $("#modal_upd").modal('show');
    }
  })
    
  $('#update_start_date').datetimepicker({
    locale: 'zh-tw',
    viewMode: 'years',
    format: 'YYYY-MM-DD',
    minDate: new Date().yyyymmdd()                  
  });

  $('#update_start_time').datetimepicker({
    locale: 'zh-tw',
    format: 'HH點 mm分'        
  });

  $("#update_start_time").on("dp.change", function (e) {
    var start_seconds = HmsToSecond($("#update_start_time").val().replace("點 ", ":").replace("分", ":00"));
    var scheduleDuration = parseInt($("#scheduleList tbody tr.success td:nth-child(7)").text());
    $("#update_end_time").val(secondsToHms(start_seconds + scheduleDuration).replace(":", "點 ").replace(":", "分 ") + "秒");          
  });

  $(document).on('click', '#modal_update_confirm', function() {
    var updateStartDate = $("#update_start_date").val();
    var updateStartTime = $("#update_start_time").val().replace("點 ", ":").replace("分", ":00");
    var updateEndTime = $("#update_end_time").val().replace("點 ", ":").replace("分 ", ":").replace("秒", "");
    if(updateStartDate == "") {
      swal("糟糕...", "您還沒選擇播放日期", 'warning');
      return ;
    }
    else if (updateStartTime == "") {
      swal("糟糕...", "您還沒選擇播放時間", 'warning');
      return ;
    }
    var selectedRow = $("#scheduleList tbody tr.success");
    selectedRow.children()[2].innerHTML = updateStartDate;
    selectedRow.children()[3].innerHTML = updateStartTime;
    selectedRow.children()[4].innerHTML = updateEndTime;
    tableRefactor();
    $("#modal_upd").modal('hide');
  });

  $(document).on('click', 'button[type=reset]', function() {
    location.href="<?php echo base_url('Schedule/timeline');?>"
  })

  $(document).on('click', '#btn_save_job', function() {
    var datas = [];
    var firstRow = $("#scheduleList tbody tr:first");
    var time_max = firstRow.children()[2].innerHTML + firstRow.children()[3].innerHTML;
    var scheduleConflict = false;
    $.each($("#scheduleList tbody tr"), function(index, row) {
      var rowID = $(this).children()[0].innerHTML;
      var rowTitle = $(this).children()[1].innerHTML;
      var rowStartDate = $(this).children()[2].innerHTML;
      var rowStartTime = $(this).children()[3].innerHTML;
      var rowEndTime = $(this).children()[4].innerHTML;
      if(rowStartDate == "-" && rowStartTime == "-") return ;
      if(rowStartDate + rowStartTime < time_max) {
        sweetAlert("糟糕...", "您的排程：" + rowTitle + " 似乎與其他排程的播放時間衝突", "error");
        scheduleConflict = true;
        return false;                                      
      }
      else
        time_max = rowStartDate + rowEndTime;
      tmp = {
        'id': rowID,
        'startDate': rowStartDate,
        'startTime': rowStartTime,
        'endTime': rowEndTime
      }
      datas.push(tmp);
    });
    if(scheduleConflict) return false;
    $.ajax({
      url: "<?php echo base_url('Schedule/timeline_confirm'); ?>",
      type: 'POST',
      data: {datas: datas},
      dataType: 'text',
      success: function(msg) {
        console.log(msg);
        $("#btn_save_job").text("送出");
        $("#btn_save_job").prop('disabled', false);
        swal({
          title: "更新成功",
          type: "success"
        },function(){
          location.href="<?php echo base_url('Schedule');?>";
        });
      },
      beforeSend: function() {
        $("#btn_save_job").text("處理中...");
        $("#btn_save_job").prop('disabled', true);
      },
      error: function(jqxhr, textStatus, errorThrown ) {
        console.log(jqxhr, textStatus, errorThrown);
      }
    });
  })

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
  
  function secondsToHms(d) {
    d = Number(d);
    var h = Math.floor(d / 3600);
    var m = Math.floor(d % 3600 / 60);
    var s = Math.floor(d % 3600 % 60);
    return ( ((h < 10 ? "0" : "") + h) + ":" + ((m < 10 ? "0" : "") + m) + ":" + ((s < 10 ? "0" : "") + s) );
  }

  function tableRefactor() {
    var rows = $('#scheduleList tbody tr').get();
    rows.sort(function(a, b) {
      var A = $(a).children('td').eq(2).text() + $(a).children('td').eq(3).text();
      var B = $(b).children('td').eq(2).text() + $(b).children('td').eq(3).text();
      if(A < B) {
        return -1;
      }

      if(A > B) {
        return 1;
      }
      return 0;
    });

    $.each(rows, function(index, row) {
      $('#scheduleList').children('tbody').append(row);
    });  
  }
})
</script>
