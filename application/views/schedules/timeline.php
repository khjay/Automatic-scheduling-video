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
                  <td><?php echo trim(str_replace($schedule_item['startDate'], '', $schedule_item['startTime'])); ?></td>
                  <td><?php echo trim(str_replace($schedule_item['startDate'], '', $schedule_item['endTime'])); ?></td>
                  <td><?php echo formatLabel(strtotime($schedule_item['startTime']), strtotime($schedule_item['endTime'])); ?></td>
                  <td><?php echo $schedule_item['s_diff'];?></td>
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
    <button class="btn btn-lg btn-primary" style="margin-right: 25px;">更新</button>
    <button class="btn btn-lg btn-danger">取消</button>
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
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
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
      var selectedTitle = $("#scheduleList tbody tr.success td:nth-child(2)").text();
      var selectedStartDate = $("#scheduleList tbody tr.success td:nth-child(3)").text();
      var selectedStartTime = $("#scheduleList tbody tr.success td:nth-child(4)").text().slice(0, -3).replace(":", "點 ") + "分";
      var selectedEndTime = $("#scheduleList tbody tr.success td:nth-child(5)").text().replace(":", "點 ").replace(":", "分 ") + "秒"
      var selectedId = $("#scheduleList tbody tr.success td:nth-child(1)").text();
      $("#modal_upd").find('.modal-title').text('編輯排程: ' + selectedTitle);
      $("#update_title").val(selectedTitle);
      $("#update_schedule_id").val(selectedId);
      $("#update_start_date").val(selectedStartDate);
      $("#update_start_time").val(selectedStartTime);
      $("#update_end_time").val(selectedEndTime);
      $("#modal_upd").modal('show');
    }
  })
    
  $('#update_start_date').datetimepicker({
    viewMode: 'years',
    format: 'YYYY-MM-DD',
    minDate: new Date().yyyymmdd()                  
  });

  $('#update_start_time').datetimepicker({
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
    var selectedRow = $("#scheduleList tbody tr.success");
    selectedRow.children()[2].innerHTML = updateStartDate;
    selectedRow.children()[3].innerHTML = updateStartTime;
    selectedRow.children()[4].innerHTML = updateEndTime;
    tableRefactor();
    $("#modal_upd").modal('hide');
  });

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
