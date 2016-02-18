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
      tableRefactor();
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
    var selectedRow = $("#playList tbody tr.success");
    if(selectedRow.index() == 0) {
      selectedRow.children()[3].innerHTML = updateStartTime;
      selectedRow.children()[4].innerHTML = updateEndTime;
      tableRefactor(0);
    }
    else {
      if(updateStartTime <= selectedRow.prev().children()[4].innerHTML) {
        tableRefactor(selectedRow.index()-1);
      }
      else {
        selectedRow.children()[3].innerHTML = updateStartTime;
        selectedRow.children()[4].innerHTML = updateEndTime;
        tableRefactor(selectedRow.index());
      }
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
