<!DOCTYPE html>
<!--
This is the operator layout header. It is largely identical to the
administrator header but the title and namespace are adjusted. By
maintaining a separate copy we avoid mixing assets across roles.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TokoKu | Operator</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/vendor/admin/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/vendor/admin/dist/css/adminlte.min.css?v=3.2.0">

  <!-- Custom global theme styles: purple gradient buttons and cards -->
  <style>
    :root {
      --primary-gradient-start: #667eea;
      --primary-gradient-end: #764ba2;
    }
    /* Apply gradient to primary and success buttons */
    .btn-primary,
    .btn-success {
      background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%) !important;
      border: none !important;
      color: #fff !important;
    }
    .btn-danger {
      background: linear-gradient(135deg, #e3342f 0%, #f75f5f 100%) !important;
      border: none !important;
      color: #fff !important;
    }
    /* Soften card appearance */
    .card,
    .shadow,
    .rounded {
      border-radius: 12px !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
    /* Gradient navbar header */
    .navbar,
    .main-header {
      background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%) !important;
      color: #fff;
    }
    .navbar .nav-link i {
      color: #fff;
    }
  </style>
</head>
<body class="hold-transition">
<div class="wrapper">

  <!-- /.navbar -->
  <style>
         .content-wrapper {
           background: linear-gradient(135deg, hsl(250, 100%, 84%), hsl(250, 82%, 62%));
         }
    </style>