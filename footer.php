    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <footer class="main-footer">
    <div class="pull-left hidden-xs">
      <strong>Copyright &copy; 2018 <a href="#">জন সহায়ক - পশ্চিম মেদিনীপুর জেলা প্রশাসন</a>.</strong> All rights
    reserved.
    </div>
   <div class="pull-right hidden-xs">
Powered By&nbsp;<a href="http://infotechsystems.in" target="_blank">Infotech Systems</a></p>
    </div> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    
  </footer>
</div>

<script src="<?php echo $full_url; ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo $full_url; ?>/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo $full_url; ?>/bower_components/fastclick/lib/fastclick.js"></script>
<script src="<?php echo $full_url; ?>/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $full_url; ?>/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script src="<?php echo $full_url; ?>/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?php echo $full_url; ?>/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php echo $full_url; ?>/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="<?php echo $full_url; ?>/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo $full_url; ?>/dist/js/adminlte.min.js"></script>
<script src="<?php echo $full_url; ?>/dist/js/demo.js"></script>
<script src="<?php echo $full_url; ?>/plugins/daterangepicker/daterangepicker.js"></script>

<script>

  $(function () {
    $('.select2').select2()
	 $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    $('[data-mask]').inputmask()
	$('#reservation').daterangepicker({format: 'DD/MM/YYYY'});

	$('.datetimemask').inputmask({
        mask: "1/2/y h:s:s",
        placeholder: "mm/dd/yyyy hh:mm:ss",
        alias: "datetime",
        hourFormat: "24"
    });

	$('#example1').DataTable({
	 'autoWidth'   : false,	
	 'pageLength': 50
	})
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
	  'pageLength': 50
    })
  })
  
</script>
</body>
</html>
<?php $conn=null; ?>