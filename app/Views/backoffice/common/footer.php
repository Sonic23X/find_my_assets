
            <div class="container-fluid mt-4"> <br> </div>
          </div>
        </div>
      </div>

      <nav class="main-header nav bottom-navbar fixed-bottom">
        <a href="#" id="inventario" class="nav-item-botom">
          <i class="fas fa-warehouse nav__icon"></i>
          <span class="nav__text">Inventario</span>
        </a>
        <a href="#" id="scanner" class="nav-item-botom">
          <i class="fas fa-cloud-upload-alt nav__icon"></i>
          <span class="nav__text">Cargar</span>
        </a>
        <a href="#" id="home" class="nav-item-botom active">
          <i class="fas fa-home nav__icon"></i>
          <span class="nav__text">Inicio</span>
        </a>
        <a href="#" id="historico" class="nav-item-botom">
          <i class="fas fa-cloud-download-alt nav__icon"></i>
          <span class="nav__text">Bajar</span>
        </a>
        <a href="#" id="notify" class="nav-item-botom">
          <i class="fas fa-clipboard-list nav__icon"></i>
          <span class="nav__text">Mantener</span>
        </a>
      </nav>

      <!-- footer -->


    </div>
    <!-- jQuery library -->
    <script src="./resources/plugins/jquery/jquery-3.5.1.min.js"></script>

    <!-- PopperJS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <!-- Font Awesome 5.13.1 -->
    <script src="./resources/plugins/fontawesome/js/all.min.js"></script>

    <!-- AdminLTE script -->
    <script src="./resources/plugins/admin-lte/js/adminlte.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>

    <!-- QR scanner -->
    <script type="text/javascript" src="./resources/plugins/qr/qr_packed.js"></script>

    <!-- BarCode scanner -->
    <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script>
    <script type="text/javascript" src="./resources/plugins/barcode/quagga.min.js"></script>

    <!-- Custom JS -->
    <script src="./resources/js/backoffice/<?= $js ?>.js"></script>

  </body>
</html>
