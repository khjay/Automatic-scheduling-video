<style>
#scheduleList tr > *:nth-child(1) {
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
              </tr>
            </thead>
            <tbody>
              <?php foreach($schedule_data as $schedule_item): ?>
                <tr>
                  <th><?php echo $schedule_item['id']; ?></td>
                  <td><?php echo $schedule_item['title']; ?></td>
                  <td><?php echo $schedule_item['startDate']; ?></td>
                  <td><?php echo str_replace($schedule_item['startDate'], '', $schedule_item['startTime']); ?></td>
                  <td><?php echo str_replace($schedule_item['startDate'], '', $schedule_item['endTime']); ?></td>
                  <td><?php echo formatLabel(strtotime($schedule_item['startTime']), strtotime($schedule_item['endTime'])); ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
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
})
</script>
