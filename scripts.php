<!--**********************************
Scripts
***********************************-->


<!-- Required vendors -->
<script src="./vendor/global/global.min.js"></script>
<script src="./js/quixnav-init.js"></script>
<script src="./js/custom.min.js"></script>
<script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="./js/main.js"></script>

<?php

$page = basename($_SERVER['PHP_SELF']);

if($page == 'dashboard.php' || $page == 'template.php') { ?>

  <!-- Vectormap -->
  <script src="./vendor/raphael/raphael.min.js"></script>
  <script src="./vendor/morris/morris.min.js"></script>


  <script src="./vendor/circle-progress/circle-progress.min.js"></script>
  <script src="./vendor/chart.js/Chart.bundle.min.js"></script>

  <script src="./vendor/gaugeJS/dist/gauge.min.js"></script>

  <!--  flot-chart js -->
  <script src="./vendor/flot/jquery.flot.js"></script>
  <script src="./vendor/flot/jquery.flot.resize.js"></script>

  <!-- Owl Carousel -->
  <script src="./vendor/owl-carousel/js/owl.carousel.min.js"></script>

  <!-- Counter Up -->
  <script src="./vendor/jqvmap/js/jquery.vmap.min.js"></script>
  <script src="./vendor/jqvmap/js/jquery.vmap.usa.js"></script>
  <script src="./vendor/jquery.counterup/jquery.counterup.min.js"></script>


  <script src="./js/dashboard/dashboard-1.js"></script>
<?php } else if($page == 'index.php' || $page == 'signup.php') { ?>

  <!-- Jquery Validation -->
  <script src="./vendor/jquery-validation/jquery.validate.min.js"></script>
  <!-- Form validate init -->
  <script src="./js/plugins-init/jquery.validate-init.js"></script>

<?php } else {

}?>
