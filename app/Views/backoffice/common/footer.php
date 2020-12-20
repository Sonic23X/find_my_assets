
            <div class="container-fluid mt-4"> <br> </div>
          </div>
        </div>
      </div>

      <nav class="main-header nav bottom-navbar fixed-bottom">
        <a href="<?= base_url('dashboard') ?>" class="nav-item-botom <?php if ( $dashboard ) { ?> active <?php } ?>">
          <i class="fas fa-home nav__icon"></i>
          <span class="nav__text">Inicio</span>
        </a>
        <a href="<?= base_url('alta') ?>" class="nav-item-botom <?php if ( $carga ) { ?> active <?php } ?>">
          <i class="fas fa-cloud-upload-alt nav__icon"></i>
          <span class="nav__text">Cargar</span>
        </a>
        <a href="<?= base_url('inventario') ?>" class="nav-item-botom <?php if ( $inv ) { ?> active <?php } ?>">
          <i class="fas fa-warehouse nav__icon"></i>
          <span class="nav__text">Inventario</span>
        </a>
        <a href="<?= base_url('bajas') ?>" class="nav-item-botom <?php if ( $bajas ) { ?> active <?php } ?>">
          <i class="fas fa-cloud-download-alt nav__icon"></i>
          <span class="nav__text">Bajar</span>
        </a>
      </nav>

      <!-- footer -->


    </div>
    <!-- jQuery library -->
    <script src="<?= base_url( ) ?>/resources/plugins/jquery/jquery-3.5.1.min.js"></script>

    <!-- PopperJS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <!-- Font Awesome 5.13.1 -->
    <script src="<?= base_url( ) ?>/resources/plugins/fontawesome/js/all.min.js"></script>

    <!-- AdminLTE script -->
    <script src="<?= base_url( ) ?>/resources/plugins/admin-lte/js/adminlte.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>

    <!-- QR scanner -->
    <script type="text/javascript" src="<?= base_url( ) ?>/resources/plugins/qr/qr_packed.js"></script>

    <!-- BarCode scanner -->
    <script type="text/javascript" src="https://unpkg.com/@zxing/library@0.15.2/umd/index.min.js"></script>
    <script type="text/javascript" src="<?= base_url( ) ?>/resources/plugins/barcode/quagga.min.js"></script>

    <!-- stepper -->
    <script rel="stylesheet" href="<?= base_url( ) ?>/resources/plugins/stepper/stepper.min.js"></script>

    <!-- datatables -->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.js"></script>

    <!-- Switch bootstrap4 -->
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?= base_url( ) ?>/resources/js/backoffice/<?= $js ?>.js"></script>

  </body>
</html>
