<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $page_title; ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/logo.png') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/Ionicons/css/ionicons.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/AdminLTE.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/skins/_all-skins.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/select2/dist/css/select2.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/fileinput/fileinput.min.css') ?>">
  <!-- <link rel="stylesheet" href="<php echo base_url('assets/bower_components/style/style.css') ?>"> -->
  <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/style/main.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/iCheck/all.css') ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style>
    /* Style untuk sidebar pada layar kecil (<= 768px) */
    @media (max-width: 768px) {
      .main-sidebar {
        position: fixed;
        top: 0;
        left: -250px;
        /* Sidebar dimulai dalam keadaan tersembunyi */
        width: 250px;
        /* Lebar sidebar */
        height: 100%;
        /* Memastikan sidebar mencakup seluruh tinggi layar */
        background-color: #222d32;
        z-index: 1000;
        transition: left 0.3s ease;
        /* Transisi halus untuk membuka/tutup sidebar */
      }

      /* Sidebar terbuka (sidebar-open) */
      .main-sidebar.sidebar-open {
        left: 0;
        /* Sidebar akan bergerak ke kiri untuk menampilkan menu */
      }

      /* Pastikan konten di body tidak tumpang tindih dengan sidebar */
      .content-wrapper {
        margin-left: 0 !important;
      }

      /* Tampilkan menu dalam sidebar dengan benar */
      .sidebar-menu a {
        text-align: left;
        font-size: 16px;
      }
    }

    /* Untuk perangkat yang lebih kecil (<= 480px) */
    @media (max-width: 480px) {
      .main-sidebar {
        width: 100%;
        /* Sidebar memanfaatkan 100% lebar layar pada perangkat kecil */
        left: -100%;
        /* Sidebar dimulai dengan posisi tersembunyi sepenuhnya */
      }

      /* Sidebar terbuka pada handphone */
      .main-sidebar.sidebar-open {
        left: 0;
        /* Sidebar akan terbuka sepenuhnya */
      }

      /* Tampilkan menu dengan ukuran yang sesuai */
      .sidebar-menu a {
        font-size: 14px;
        /* Ukuran font lebih kecil untuk layar kecil */
      }
    }
  </style>

  <script src="<?php echo base_url('assets/bower_components/jquery/dist/jquery.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/dist/js/adminlte.min.js') ?>"></script>
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <script>
    $(document).ready(function() {
      // Toggle sidebar untuk mobile
      $('.sidebar-toggle').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-collapse'); // Toggle class pada body untuk collapse sidebar
        $('.main-sidebar').toggleClass('sidebar-open'); // Toggle class pada sidebar untuk membuka/menutup
      });

      // Menutup sidebar saat menu diklik (untuk layar kecil)
      $('.sidebar-menu a').on('click', function() {
        if ($(window).width() <= 480) {
          // Jika lebar layar <= 480px, tutup sidebar setelah menu diklik
          $('body').addClass('sidebar-collapse'); // Menambahkan kelas untuk menyembunyikan sidebar
          $('.main-sidebar').removeClass('sidebar-open'); // Menghapus kelas untuk membuka sidebar
        }
      });
    });
  </script>
  <script src="<?php echo base_url('assets/bower_components/jquery-knob/dist/jquery.knob.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/bower_components/bootstrap-daterangepicker/daterangepicker.js') ?>"></script>
  <script src="<?php echo base_url('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/bower_components/fastclick/lib/fastclick.js') ?>"></script>
  <script src="<?php echo base_url('assets/bower_components/select2/dist/js/select2.full.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/dist/js/pages/dashboard.js') ?>"></script>
  <script src="<?php echo base_url('assets/dist/js/demo.js') ?>"></script>
  <script src="<?php echo base_url('assets/plugins/fileinput/fileinput.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/plugins/iCheck/icheck.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') ?>"></script>

</head>

<body class="hold-transition skin-green sidebar-mini">
  <div class="wrapper">