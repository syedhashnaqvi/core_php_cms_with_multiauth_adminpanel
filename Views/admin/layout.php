<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php print $title; ?></title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php asset('admin/plugins/fontawesome-free/css/all.min.css') ?>">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Tempusdominus Bootstrap 4 -->
	<link rel="stylesheet" href="<?php asset('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')?>">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?php asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')?>">
	<!-- JQVMap -->
	<link rel="stylesheet" href="<?php asset('admin/plugins/jqvmap/jqvmap.min.css')?>">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php asset('admin/css/adminlte.min.css')?>">
	<link rel="stylesheet" href="<?php asset('admin/css/custom.css')?>">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?php asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')?>">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="<?php asset('admin/plugins/daterangepicker/daterangepicker.css')?>">
	<!-- summernote -->
	<link rel="stylesheet" href="<?php asset('admin/plugins/summernote/summernote-bs4.min.css')?>">
	<?php print $this->block('head.css'); ?>
	</head>
	<body class="hold-transition sidebar-mini layout-fixed">
		<div class="wrapper">
			<?php include('partials/header.php'); ?>
			<?php include ('partials/aside.php'); ?>

			<?php print $this->block('content'); ?>

			<?php print $this->load('admin/footer'); ?>

		</div>

		<!-- jQuery -->
		<script src="<?php asset('admin/plugins/jquery/jquery.min.js')?>"></script>
		<!-- jQuery UI 1.11.4 -->
		<script src="<?php asset('admin/plugins/jquery-ui/jquery-ui.min.js')?>"></script>
		<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
		<!-- <script src="<?php //asset('admin/js/jquery.form.min.js')?>"></script> -->
		<script>
		$.widget.bridge('uibutton', $.ui.button)
		</script>
		<!-- Bootstrap 4 -->
		<script src="<?php asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
		<!-- ChartJS -->
		<script src="<?php asset('admin/plugins/chart.js/Chart.min.js')?>"></script>
		<!-- Sparkline -->
		<script src="<?php asset('admin/plugins/sparklines/sparkline.js')?>"></script>
		<!-- JQVMap -->
		<script src="<?php asset('admin/plugins/jqvmap/jquery.vmap.min.js')?>"></script>
		<script src="<?php asset('admin/plugins/jqvmap/maps/jquery.vmap.usa.js')?>"></script>
		<!-- jQuery Knob Chart -->
		<script src="<?php asset('admin/plugins/jquery-knob/jquery.knob.min.js')?>"></script>
		<!-- daterangepicker -->
		<script src="<?php asset('admin/plugins/moment/moment.min.js')?>"></script>
		<script src="<?php asset('admin/plugins/daterangepicker/daterangepicker.js')?>"></script>
		<!-- Tempusdominus Bootstrap 4 -->
		<script src="<?php asset('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')?>"></script>
		<!-- Summernote -->
		<script src="<?php asset('admin/plugins/summernote/summernote-bs4.min.js')?>"></script>
		<!-- overlayScrollbars -->
		<script src="<?php asset('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')?>"></script>
		<!-- AdminLTE App -->
		<script src="<?php asset('admin/js/adminlte.js')?>"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="<?php asset('admin/js/demo.js')?>"></script>
		<?php print $this->block('footer.javascript'); ?>
	</body>
</html>