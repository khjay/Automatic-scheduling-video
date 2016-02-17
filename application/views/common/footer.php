    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url('public/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url('public/bower_components/metisMenu/dist/metisMenu.min.js');?>"></script>

    <!-- Sweet Alert -->
    <script src="<?php echo base_url('public/bower_components/sweetalert/dist/sweetalert.min.js');?>"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url('public/dist/js/sb-admin-2.js');?>"></script>

    <!-- moment -->
    <script src="<?php echo base_url('public/bower_components/moment/min/moment.min.js');?>"></script>

    <!-- datetimepicker -->
    <script src="<?php echo base_url('public/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js');?>"></script>
    <script>
      $(function() {
         Date.prototype.yyyymmdd = function() {
          var yyyy = this.getFullYear().toString();
          var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
          var dd  = this.getDate().toString();
          return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
         };
       });
    </script>

</body>

</html>

