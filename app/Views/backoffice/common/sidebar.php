

      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <div class="sidebar">
          <div class="user-panel mt-3 pb-3 mb-3">
            <div class="sidebar-profile">
              <div class="image">
                <img src="./images/backoffice/profile.png" class="img-circle elevation-2" alt="User Image">
              </div>
              <br>
              <div class="info mt-2">
                <a href="<?= base_url( '/perfil' ) ?>" class="d-block">Hola, <?= $name ?></a>
              </div>
            </div>
          </div>
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <li class="nav-item">
                <a href="<?= base_url( '/dashboard' ) ?>" class="nav-link">
                  <i class="nav-icon fas fa-home"></i>
                  Inicio
                </a>
              </li>
              <li class="nav-header">Activos</li>
              <li class="nav-item">
                <a href="<?= base_url( '/carga' ) ?>" class="nav-link">
                  <i class="nav-icon fas fa-cloud-upload-alt"></i>
                  Carga de activos
                </a>
              </li>
              <li class="nav-header">Mantenedores</li>
              <li class="nav-item">
                <a href="<?= base_url( '/usuarios' ) ?>" class="nav-link">
                  <i class="nav-icon fas fa-user-plus"></i>
                  Usuarios
                </a>
              </li>
              <li class="nav-item d-none">
                <a href="<?= base_url( '/empresas' ) ?>" class="nav-link">
                  <i class="nav-icon fas fa-hotel"></i>
                  Empresas y locaciones
                </a>
              </li>
              <li class="nav-item d-none">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-laptop"></i>
                  Tipos de activos
                </a>
              </li>
              <li class="nav-header"></li>
              <li class="nav-header"></li>
              <li class="nav-item d-none">
                <a href="<?= base_url( '/pagos' ) ?>" class="nav-link">
                  <i class="nav-icon fas fa-id-card"></i>
                  Medios de pago y facturación
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url( '/salir' ) ?>" class="nav-link">
                  <i class="nav-icon fas fa-sign-out-alt"></i>
                  Cerrar sesión
                </a>
              </li>
            </ul>
          </nav>

        </div>

      </aside>
