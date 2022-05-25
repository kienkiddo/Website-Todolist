<footer class="container-fluid text-left text-lg-start text-dark bg-light" style="margin-top: 50px;">
  <div class="container" style="padding: 20px">
    <div class="text-center mt-5">
      <small>TỪ THỰC TẬP CƠ SỞ PTIT 2022</br>Giáo viên hướng dẫn: Ths. Nguyễn Hoàng Anh</small>
    </div>
  </div>
</footer>
</body>

<!-- Bootstrap core JavaScript -->

<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


<?php
if (isset($_SESSION['notify'])) :
  $notify = unserialize($_SESSION['notify']);
?>
  <script>
    $.notify("<?= $notify->message ?>", "<?= $notify->type ?>");
  </script>
<?php
  unset($_SESSION['notify']);
endif
?>