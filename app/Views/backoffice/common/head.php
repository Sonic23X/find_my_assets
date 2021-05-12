<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">

  	<meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $title ?></title>

    <link rel="icon" href="<?= base_url( ) ?>/favicon.png">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- AdminLTE style -->
    <link rel="stylesheet" href="<?= base_url( ) ?>/resources/plugins/admin-lte/css/adminlte.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">

    <!-- Leaflet -->
    <link rel="stylesheet" href="<?= base_url( ) ?>/resources/plugins/leaflet/leaflet.css">
    <script type="text/javascript" src="<?= base_url( ) ?>/resources/plugins/leaflet/leaflet.js"></script>
    
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />

    <!-- stepper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">

    <!-- Switch bootstrap4 -->
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.css"/>

    <!-- Select2 -->
    <link href="<?= base_url( ) ?>/resources/plugins/select2/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url( ) ?>/resources/plugins/select2/select2-bootstrap4.min.css">
    <script src="<?= base_url( ) ?>/resources/plugins/select2/select2.min.js" async></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url( ) ?>/resources/css/backoffice/<?= $css ?>.css">

  </head>
  <body class="hold-transition sidebar-collapse">
    <div class="wrapper">
