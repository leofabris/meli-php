<?php

$dados['sellerid'] = "";
$dados['producao_key'] = "";
$dados['producao_token'] = "";

session_start();
$_SESSION['seller_id'] = $dados['sellerid'];
$_SESSION['producao_key'] = $dados['producao_key'];
$_SESSION['producao_token'] = $dados['producao_token'];

?>

<!DOCTYPE html>
<html lang="pt">

<head>
  <?php include_once('./imports/header.php'); ?>
  <title>Consultas API do ML</title>
  <?php include_once('./imports/imports.php'); ?>
</head>

<body>
  <?php include_once('./imports/navbar.php'); ?>


  <div class="container">
    <p id="carregando" class="text-center" style="display: none"><i class="fa fa-refresh fa-spin"></i><br>Carregando...</p>
  </div>

  <?php include_once('./imports/scripts.php'); ?>
</body>

</html>