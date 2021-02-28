
            <div class="home">

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
                      <div id="globalMap" style="height: 700px;"></div>
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
