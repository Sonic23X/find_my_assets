
            <div class="down d-none">

              <div class="row text-center">
                <div class="col-12 col-sm-12 col-md-12 text-center title-downs">
                  <b>Cierra el ciclo de tus activos</b>
                </div>
              </div>

              <div class="row mt-3 p-2 instructions text-center">
                <div class="col-12 col-sm-12 text-center">
                  <span id="down-instructions">Selecciona uno tus activos y confirma su baja</span>
                </div>
              </div>

              <div class="card">
                <div class="card-body">

                  <div class="row mb-3">
                    <div class="col-6 float-left align-middle">
                      <span>Total de activos: <b class="down-count">XX</b> </span>
                    </div>
                    <div class="col-6 float-right">

                    </div>
                  </div>

                  <div class="card collapsed-card">
                    <div class="card-header text-center filtros-card-background">
                      <span class="text-white">Filtros</span>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" style="color: white">
                          <i class="fas fa-plus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="text-center">
                        <div class="form-group row">
                          <label for="tipoActivo" class="col-sm-2 col-form-label">Tipo de activo</label>
                          <div class="col-sm-10">
                            <select class="custom-select iTipoActivo" id="downTipo" name="tipoActivo" onchange="downFiltros( )">
                              <option value="">Todos</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="cCosto" class="col-sm-2 col-form-label">Centro de costo</label>
                          <div class="col-sm-10">
                            <select class="custom-select" id="downFCC" name="cCosto" onchange="downFiltros( )">
                              <option value="">Todas</option>
                              <option value="1">Administración</option>
                              <option value="2">Producción</option>
                              <option value="3">Marketing</option>
                              <option value="4">Comercial</option>
                            </select>
                          </div>
                        </div>


                        <div class="row">
                          <div class="col-12 text-center">
                            <span><b>Ubicación</b></span>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="asignacion" class="col-sm-2 col-form-label">Empresa</label>
                          <div class="col-sm-10">
                            <select class="custom-select iEmpresa" id="downEmpresa" name="asignacion" onchange="downFiltros( )">
                              <option value="">Todos</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="asignacion" class="col-sm-2 col-form-label">Sucursal</label>
                          <div class="col-sm-10">
                            <select class="custom-select iSucursal" id="downSucursal" name="asignacion" onchange="downFiltros( )">
                              <option value="">Todas</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="asignacion" class="col-sm-2 col-form-label">Área</label>
                          <div class="col-sm-10">
                            <select class="custom-select iArea" id="downArea" name="asignacion" onchange="downFiltros( )">
                              <option value="">Todas</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="mt-2 mb-2 delete-button-down d-none">
                    <button type="button" class="btn btn-sm btn-danger btn-block" onclick="multipleDelete( )">Eliminar</button>
                  </div>

                  <div class="mt-3 table-responsive text-center">
                    <table class="table table-hover table-down-actives-content">
                      <thead>
                        <tr>
                          <th scope="col">Activo</th>
                          <th scope="col">Asignación</th>
                          <th scope="col">Cargado</th>
                        </tr>
                      </thead>
                      <tbody class="table-down-actives">

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Modal -->
              <div class="modal fade" id="deleteActivo" tabindex="-1" role="dialog" aria-labelledby="deleteActivoLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-body p-5 text-center">
                      <div class="row">
                        <h4>¿Por qué estás bajando este activo?</h4>
                      </div>

                      <div class="row mt-2">
                        <div class="form-group">
                          <label>Ingresa un motivo</label>
                          <select class="custom-select" id="down-select">
                            <option value="0">Tiempo de vida completo</option>
                            <option value="1">Venta del activo</option>
                          </select>
                        </div>
                      </div>

                      <div class="row mt-2 motivo-down-form">
                        <div class="form-group w-100">
                          <label>Ingresa el motivo de la baja</label>
                          <textarea class="form-control" id="motivo-down" rows="2"></textarea>
                        </div>
                      </div>

                      <div class="row down-aviso">
                        <p>
                          <b>importante: </b> <span class="text-mute">Este motivo se asociará a cada uno de los activos seleccionados y no podrá ser reversado </span>
                        </p>
                      </div>

                      <div class="row mt-2">
                        <button type="button" class="btn btn-primary btn-sm btn-block" onclick="confirmDownDelete( )">Confirmar baja</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal fade" id="downInfoModal" tabindex="-1" role="dialog" aria-labelledby="downInfoModallLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="downInfoModalLabel">Detalles del activo</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form class="active-inventary-form">

                        <div class="form-group row">
                          <label for="tipoActivo" class="col-sm-4 col-form-label">Tipo de activo</label>
                          <div class="col-sm-8">
                            <select class="form-control-plaintext iTipoActivo" name="tipoActivo" id="downTipoActivo" disabled>

                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="name" class="col-sm-4 col-form-label">Nombre</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control-plaintext" id="downName" placeholder="Ej. Mackbook PRO" disabled>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="serie" class="col-sm-4 col-form-label">
                            No. de serie
                            <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="right"
                                    style="border-radius: 25px; font-size: 9px !important;"
                                    title="Campo actualizado, valor anterior: MXN56231">
                              <i class="fas fa-info"></i>
                            </button>
                          </label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control-plaintext" id="downSerie" placeholder="Ej. Nombre" disabled>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="cCosto" class="col-sm-4 col-form-label">Centro de costo</label>
                          <div class="col-sm-8">
                            <select class="form-control-plaintext" name="cCosto" id="downcCosto" disabled>
                              <option value="1">Administración</option>
                              <option value="2">Producción</option>
                              <option value="3">Marketing</option>
                              <option value="4">Comercial</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="asignacion" class="col-sm-4 col-form-label">Asignado a</label>
                          <div class="col-sm-8">
                            <select class="form-control-plaintext iAsignacion" name="asignacion" id="downAsignacion" disabled>

                            </select>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-12 text-center">
                            <span><b>Ubicación</b></span>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="asignacion" class="col-sm-4 col-form-label">Empresa</label>
                          <div class="col-sm-8">
                            <select class="form-control-plaintext iEmpresa" name="asignacion" id="downEmpresa" disabled>

                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="asignacion" class="col-sm-4 col-form-label">Sucursal</label>
                          <div class="col-sm-8">
                            <select class="form-control-plaintext iSucursal" name="asignacion" id="downSucursal" disabled>

                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="asignacion" class="col-sm-4 col-form-label">Área</label>
                          <div class="col-sm-8">
                            <select class="form-control-plaintext" name="asignacion" id="downArea" disabled>

                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="desc" class="col-sm-4 col-form-label">Descripción</label>
                          <div class="col-sm-8">
                            <textarea class="form-control-plaintext" id="downDesc" rows="3" disabled></textarea>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-12 text-center">
                            <span><b>Imagenes</b></span>
                          </div>
                        </div>

                        <div class="row text-center">
                          <div class="col-4">
                            <div class="down-image-front">
                              <i class="fas fa-5x fa-image"></i>
                            </div>
                            <br>
                            <label>Frontal</label>
                          </div>
                          <div class="col-4">
                            <div class="down-image-right">
                              <i class="fas fa-5x fa-image"></i>
                            </div>
                            <br>
                            <label>Lat. Der.</label>
                          </div>
                          <div class="col-4">
                            <div class="down-image-left">
                              <i class="fas fa-5x fa-image"></i>
                            </div>
                            <br>
                            <label>Lat. Izq.</label>
                          </div>
                        </div>

                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
