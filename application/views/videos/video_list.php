<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">
        影像列表
        <a class="btn btn-primary pull-right" href="<?php echo base_url('Video/add');?>" style="color: #fff;text-decoration: none;">新增</a>
      </h1>
    </div>
        <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading"><h1 class="panel-title">影像查詢</h1></div>
        <div class="table-responsive">
          <form action="<?php echo base_url('Video');?>" method="post">
            <table class="table table-condensed" style="margin-bottom:0">
              <thead>
                <tr>
                  <th colspan="4">搜尋條件 :</th>
                </tr>
              </thead>
              <tbody>  
                  <tr> 
                    <td width="10%" style="text-align: right;">名稱 : </td>
                    <td width="80%">
                      <input id="search_title" name="search_title" type="text" class="form-control" placeholder="影像名稱">
                    </td>
                    <td width="10%" style="text-align: right;">
                      <button type="submit" class="btn btn-primary">搜尋</button>
                    </td>
                  </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><h1 class="panel-title">影像列表</h1></div>
          <div class="table-responsive">
            <table class="table table-striped table-hover" >
              <thead>
                <tr>
                  <th>影像名稱</th>
                  <th>影像長度</th>
                  <th>加入日期</th>
                  <th>修改日期</th>
                  <th>管理</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($video_data as $video_item): ?>
                <tr>
                  <td><?php echo $video_item['title'];?></td>
                  <td><?php echo $video_item['video_length'];?></td>
                  <td><?php echo $video_item['created_at'];?></td>
                  <td><?php echo $video_item['updated_at'];?></td>
                  <td>
                    <a href="<?php echo base_url('Video/update/' . $video_item['id']);?>" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="修改">
                      <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </a>
                    <a href="<?php echo base_url('Video/remove/' . $video_item['id']);?>" class="btn btn-danger btnRemove"  data-toggle="tooltip" data-placement="top" title="刪除">
                      <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </a>
                  </td>
                </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(function() {
    $(document).on("click", ".btnRemove", function(e) {
      var targetUrl = $(this).attr("href");
      e.preventDefault();
      swal({
        title: "確定刪除嗎?",
        text: "刪除後將不能復原此筆資料!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "確定!",
        cancelButtonText: "取消!",
        closeOnConfirm: false,
        closeOnCancel: true
      },function(isConfirm){
        if (isConfirm) {
          $.ajax({
            type: "GET",
            url: targetUrl,
            dataType: "text",
            success:function(data) {
              if(data > 0) {
                swal({
                  title: "刪除成功!!",
                  type: "success"
                },function() {
                  location.reload(); 
                });
              }
              else
                swal("刪除失敗!!", "", "error");
            }
          });
        }
      });
    });
  })
</script>
