
            <div class="home">

              <!-- Filters-->

              <div class="row">
                <div class="col">
                  <div class="card card-warning">
                    <div class="card-header">
                      <h3 class="card-title text-white">Filtros Globales</h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="form-row">
                        <div class="form-group col-md">
                          <label for="tiposActivo">Tipos de activo</label>
                          <select class="custom-select" id="tiposActivo" onchange="dashFilter()">
                            <option value="0">Sin seleccion</option>
                          </select>
                        </div>
                        <div class="form-group col-md">
                          <label for="ccActivos">Centro de costo</label>
                          <select class="custom-select" id="ccActivos" onchange="dashFilter()">
                            <option value="0">Sin seleccion</option>
                          </select>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Map -->
              <div class="row">
                <div class="col">
                  <div class="card card-warning">
                    <div class="card-header">
                      <h3 class="card-title text-white">Ubicación de activos</h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="form-row">
                        <div class="form-group col-md">
                          <label for="numActivos">Cantidad</label>
                          <select class="custom-select" id="numActivos" onchange="mapFilter()">
                            <option value="10">10</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="">Todos</option>
                          </select>
                        </div>
                        <div class="form-group col-md">
                          <label for="nameActivo">Nombre del activo</label>
                          <input type="text" class="form-control" id="nameActivo" placeholder="Nombre" onkeyup="mapFilter()">
                        </div>
                      </div>
                      <div id="globalMap" class="mt-2" style="height: 700px;"></div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Box info -->
              <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                  <div class="card card-warning">
                    <div class="card-header">
                      <h3 class="card-title text-white">Valor total de activos</h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Tipo de activo</th>
                            <th style="width: 40px">Valor</th>
                          </tr>
                        </thead>
                        <tbody class="table-1-valor-activos">
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                  <div class="card card-warning">
                    <div class="card-header">
                      <h3 class="card-title text-white">Tipos de activos</h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                  <div class="card card-warning">
                    <div class="card-header">
                      <h3 class="card-title text-white">Proceso de inventario</h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                      <br>
                      <p class="text-center">
                        <span id="periodoInventario"></span>
                      </p>
                    </div>
                  </div>
                </div>
                
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                  <div class="card card-warning">
                    <div class="card-header">
                      <h3 class="card-title text-white">Ultimas altas</h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <table class="table table-striped">
                        <tbody class="table-2-activos-alta">
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                  <div class="card card-warning">
                    <div class="card-header">
                      <h3 class="card-title text-white">Ultimas bajas</h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <table class="table table-striped">
                        <tbody class="table-3-activos-baja">
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div>

            </div>
