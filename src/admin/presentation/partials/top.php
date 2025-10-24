<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// Định nghĩa BASE_URL nếu chưa có (ước lượng từ SCRIPT_NAME)
if (!defined('BASE_URL')) {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']); // e.g. /CSDL-School-Management/src/admin/index.php
    $parts = explode('/src/', $script, 2);
    $base = isset($parts[0]) ? rtrim($parts[0], '/') : '';
    if ($base === '/') $base = '';
    define('BASE_URL', $base);
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="<?php echo BASE_URL; ?>/images/mylogo.png" rel="icon" type="image/png" />
    <title>UET Management</title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/plugins/datatable/datatables.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Raleway', sans-serif;
    }
    </style>
    <script src="<?php echo BASE_URL; ?>/js/jquery.js"></script>
    <script src="<?php echo BASE_URL; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/plugins/datatable/datatables.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/plugins/table2excel.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/gh/rainabba/jquery-table2excel/dist/jquery.table2excel.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" 
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  </head>

  <body>