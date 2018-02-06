<?php

include_once('api-includes.php');
include_once('controller.php');

$controller = new Controller();
$bbb = $controller->bbb;
$controller->handle();
?>

<title>Беседка | <?php echo $controller->title ?></title>

<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="Cache-Control" content="no-cache">
<link type="image/x-icon" href="../images/favicon.ico" rel="shortcut icon">

<link rel="stylesheet" href="css/bootstrap.paper.min.css">
<link rel="stylesheet" href="css/styles.css">

<script src="js/jquery-3.2.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->
