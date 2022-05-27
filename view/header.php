<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>TO DO LIST</title>
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="asset/main.css" rel="stylesheet" type="text/css">
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/notify/notify.js"></script>
</head>

<body class="">
  <div class="container-fluid" style="padding: 0;">
    <nav class="navbar navbar-expand-lg navbar-info bg-info" id="nav">
      <div class="container">
        <a class="navbar-brand" href="<?= $home ?>">
          <img src="image/logo.png" alt="" style="width:50px; height: 50px">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
          </ul>
          <?php
          if ($login) : ?>
            <ul class="navbar-nav ml-auto text-center">
              <li class="nav-item li-login">
                </li>
                <a class="nav-link text-uppercase font-weight-bold btn-login" href="index.php?page=user" style="color: white"><i class="fas fa-user"></i> <?= $member->getName() ?></a>
              <li class="nav-item">
                <a class="nav-link text-uppercase font-weight-bold btn-register" href="controller.php?router=logout" style="color: white" type="button" ><i class="fas fa-sign-out-alt"></i> ĐĂNG XUẤT</a>
              </li>
            </ul>
          <?php else : ?>
            <ul class="navbar-nav ml-auto text-center">
              <li class="nav-item li-login">
                <a class="nav-link text-uppercase font-weight-bold btn-login" href="index.php?page=dang-nhap" style="color: white" type="button"><i class="fas fa-sign-in-alt"></i> ĐĂNG NHẬP</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-uppercase font-weight-bold btn-register" href="index.php?page=dang-ky" style="color: white" type="button"><i class="fas fa-user-plus"></i> ĐĂNG KÝ</a>
              </li>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </nav>
  </div>
