
            <div class="inventary d-none">

              <div class="row text-center">
                <div class="col-12 col-sm-12 col-md-12 text-center title-inv">
                  <b>Confirma y concilia tu inventario</b>
                </div>
              </div>

              <div class="row inv-step d-none">
                <div class="col-12 col-md-12 col-sm-12">
                  <div class="bs-stepper">
                    <div class="bs-stepper-header" role="tablist">
                      <div class="step" data-target="#scan-part">
                        <div class="step-trigger">
                          <span class="bs-stepper-circle select-circle" style="background: #e6c84f">1</span>
                          <span class="bs-stepper-label select-label" style="color: #e6c84f">Seleccionar</span>
                        </div>
                      </div>
                      <div class="line"></div>
                      <div class="step" data-target="#update-part-part">
                        <div class="step-trigger">
                          <span class="bs-stepper-circle confirm-circle">2</span>
                          <span class="bs-stepper-label confirm-label">Confirmar</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mt-3 p-2 instructions text-center">
                <div class="col-12 col-sm-12 text-center">
                  <span id="inv-instructions">Selecciona uno de los estados de carga</span>
                </div>
              </div>

              <div class="row mt-3 p-2 inv-buttons">
                <div class="col-12">
                  <div class="d-flex justify-content-center">
                    <div class="btn-group w-100" role="group" aria-label="Navegacion">
                      <input type="button" class="btn btn-outline-secondary w-25" value="Nuevos" id="inv-new">
                      <input type="button" class="btn btn-outline-secondary w-25" value="En proceso" id="inv-update">
                      <input type="button" class="btn btn-outline-secondary w-25" value="Inventario" id="inv-inv">
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-2 inv-news-start">

                <div class="row mt-3">

                  <div class="inv-news-table w-100 d-none">

                    <div class="inv-news-home">
                      <div class="card">
                        <div class="card-body">

                          <div class="row">
                            <div class="col-6 float-left align-middle">
                              <span>Total de activos: <b class="number-new-actives">XX</b> </span>
                            </div>
                            <div class="col-6 float-right">

                            </div>
                          </div>

                          <div class="mt-3 table-responsive text-center">
                            <table class="table table-sm table-hover table-new-items">
                              <thead>
                                <tr>
                                  <th scope="col">Activo</th>
                                  <th scope="col">Asignación</th>
                                  <th scope="col">Cargado</th>
                                  <th scope="col"></th>
                                </tr>
                              </thead>
                              <tbody class="table-new-actives">

                              </tbody>
                            </table>
                          </div>

                        </div>
                      </div>
                    </div>

                    <div class="inv-news-confirm d-none">

                      <div class="row mt-2 text-center">
                        <div class="col-12 col-sm-12 col-md-12">
                            <span id="new-subtipo"></span> / <span id="new-nombre"></span>
                          <br>
                          <span>Serie: <b id="new-serie"></b></span>
                        </div>
                      </div>

                      <div class="row mt-3 text-center">
                        <div class="col-12 col-sm-12 col-md-12">
                          <span>Asignado a <b id="new-asignacion"></b></span>
                        </div>
                      </div>

                      <div class="inv-form-conciliar d-none">
                        <div class="row mt-3 text-center">
                          <div class="col-12 col-sm-12 col-md-12">
                            <span>El activo es similar a los que tienes en tu inventario</span>
                            <br>
                            <span><b>¿Deseas conciliarlo?</b></span>
                          </div>
                        </div>

                        <div class="row mt-2 text-center">
                          <div class="col-6 col-sm-6 col-md-6">
                            <button type="button" class="btn btn-block btn-success btn-sm" id="conciliar1" onclick="IsConcilar( )">Sí</button>
                          </div>

                          <div class="col-6 col-sm-6 col-md-6">
                            <button type="button" class="btn btn-block btn-primary btn-sm" id="continueNew" onclick="NewActiveForm( )">
                              No, continuar
                            </button>
                          </div>
                        </div>
                      </div>

                      <div class="inv-form-continue-info d-none">
                        <div class="row mt-3 text-center">
                          <div class="col-12 col-sm-12 col-md-12">
                            <button type="button" class="btn btn-block btn-primary btn-sm" id="continueNew" onclick="NewActiveForm( )">
                              Continuar
                            </button>
                          </div>
                        </div>
                      </div>

                      <div class="row mt-3 text-center">
                        <div class="col-12 col-sm-12 col-md-12">
                          <span>¿Tienes algun problema con el activo cargado?</span>
                          <br>
                          <button type="button" class="btn btn-danger btn-block btn-sm" id="deleteNewActivo">Eliminar</button>
                        </div>
                      </div>

                    </div>

                    <div class="inv-news-active-new d-none">
                      <form class="active-inventary-new-active-form">

                        <div class="row">
                          <div class="col-12 text-center">
                            <span><b>Respecto a la compra</b></span>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="precio" class="col-sm-2 col-form-label">Precio del activo (CLP)</label>
                          <div class="col-sm-10">
                            <input type="number" id="clp" class="form-control" name="clp" placeholder="$1,000,000">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="fechadecompra" class="col-sm-2 col-form-label">Fecha de compra</label>
                          <div class="col-sm-10">
                            <input id="fechadecompra" type="date" class="form-control">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="name" class="col-sm-2 col-form-label">Factura/boleta</label>
                          <div class="col-sm-10">
                            <input type="file" id="factura" class="form-control-file" placeholder="Factura" onChange="setFactura(this)">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="name" class="col-sm-2 col-form-label">Garantía (opcional)</label>
                          <div class="col-sm-10">
                            <input type="file" id="garantia" class="form-control-file" placeholder="Garantia" onChange="setGarantia(this)">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="name" class="col-sm-2 col-form-label">Fecha de expiracion de garantia (opcional)</label>
                          <div class="col-sm-10">
                            <input id="fechagarantia" type="date" class="form-control">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-12 text-center">
                            <span><b>Depreciación</b></span>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="name" class="col-8 col-form-label">
                            ¿Contabilizar?
                            <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
                                    style="border-radius: 25px; font-size: 9px !important;"
                                    title="Solo será considerado para efectos de control general, no afectará el resultado financiero">
                              <i class="fas fa-info"></i>
                            </button>
                          </label>
                          <div class="col-4 d-flex justify-content-center align-items-center">
                            <input type="checkbox" checked data-toggle="toggle" data-on="Si" data-off="No"
                                   data-onstyle="warning" data-offstyle="warning" data-style="ios" id="contabilizar">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="medotoD" class="col-sm-2 col-form-label">Metodo de depreciacion</label>
                          <div class="col-sm-10">
                            <select class="custom-select" name="medotoD" id="metodo_depreciacion" onchange="setDepre( )">

                            </select>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="name" class="col-sm-2 col-form-label">Fecha de inicio</label>
                          <div class="col-sm-10">
                            <input id="fechastart" type="date" class="form-control">
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="name" class="col-sm-2 col-form-label">Vida útil ( <span id="unidad-text"></span> )</label>
                          <div class="col-sm-10">
                            <input id="vidautilnew" type="number" class="form-control">
                          </div>
                        </div>

                        <div class="form-group row mb-5">
                          <div class="col-3 col-sm-3"></div>
                          <div class="col-6 col-sm-6">
                            <button type="button" class="btn btn-success btn-block" onclick="ConfirmNew( )">
                              Activar en inventario
                            </button>
                          </div>
                          <div class="col-3 col-sm-3"></div>
                        </div>

                      </form>
                    </div>

                    <div class="inv-news-conciliar d-none">
                      <div class="card">
                        <div class="card-body">

                          <div class="row">
                            <div class="col-6 float-left align-middle">

                            </div>
                            <div class="col-6 float-right">

                            </div>
                          </div>

                          <div class="mt-3 table-responsive text-center">
                            <table class="table table-sm table-hover inventary-conciliacion-table-content">
                              <thead>
                                <tr>
                                  <th scope="col">Activo</th>
                                  <th scope="col">Asignación</th>
                                  <th scope="col">%</th>
                                </tr>
                              </thead>
                              <tbody class="inventary-conciliacion-table">

                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="inv-news-conciliar-confirm d-none">
                      <div class="card">
                        <div class="card-body">

                          <div class="row">
                            <div class="col-12 text-center">
                              <span><b>El activo nuevo</b></span>
                            </div>
                          </div>

                          <div class="row text-center">
                            <div class="table-responsive">
                              <table class="table table-hover">
                                <thead>
                                  <tr>
                                    <th scope="col">Activo</th>
                                    <th scope="col">Asignación</th>
                                    <th scope="col">Cargado</th>
                                  </tr>
                                </thead>
                                <tbody class="conciliar-new">

                                </tbody>
                              </table>
                            </div>
                          </div>

                          <div class="separator">conciliar con</div>

                          <div class="row mt-3">
                            <div class="col-12 text-center">
                              <span><b>El activo existente</b></span>
                            </div>
                          </div>

                          <div class="row text-center">
                            <div class="table-responsive">
                              <table class="table table-hover">
                                <thead>
                                  <tr>
                                    <th scope="col">Activo</th>
                                    <th scope="col">Asignación</th>
                                    <th scope="col">Cargado</th>
                                  </tr>
                                </thead>
                                <tbody class="conciliar-old">

                                </tbody>
                              </table>
                            </div>
                          </div>

                          <div class="row mt-5">
                            <div class="col-12">
                              <button type="button" class="btn btn-success btn-block btn-sm" onclick="ConfirmConciliarMsg( )">Conciliar y actualizar</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="newInvModal" tabindex="-1" role="dialog" aria-labelledby="newInvModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="newInvModalLabel">Detalles del activo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form class="active-inventary-form">

                              <div class="form-group row">
                                <label for="tipoActivo" class="col-sm-4 col-form-label">Tipo de activo</label>
                                <div class="col-sm-8">
                                  <select class="form-control-plaintext iTipoActivo" name="tipoActivo" id="newTipoActivo" disabled>
                                    <option value="">Muebles y utilidades</option>
                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Nombre</label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control-plaintext" id="newName" placeholder="Ej. Mackbook PRO" disabled>
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
                                  <input type="text" class="form-control-plaintext" id="newSerie" placeholder="Ej. Nombre" disabled>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="cCosto" class="col-sm-4 col-form-label">Centro de costo</label>
                                <div class="col-sm-8">
                                  <select class="form-control-plaintext" name="cCosto" id="newCCosto" disabled>
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
                                  <select class="form-control-plaintext iAsignacion" name="asignacion" id="newAsignacion" disabled>

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
                                  <select class="form-control-plaintext iEmpresa" name="asignacion" id="newEmpresa" disabled>

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-4 col-form-label">Sucursal</label>
                                <div class="col-sm-8">
                                  <select class="form-control-plaintext iSucursal" name="asignacion" id="newSucursal" disabled>

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-4 col-form-label">Área</label>
                                <div class="col-sm-8">
                                  <select class="form-control-plaintext iArea" name="asignacion" id="newArea" disabled>
                                    <option value="1">Sector tecnólogico</option>
                                    <option value="2">Sala de gerencia</option>
                                    <option value="3">Galpón</option>
                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="desc" class="col-sm-4 col-form-label">Descripción</label>
                                <div class="col-sm-8">
                                  <textarea class="form-control-plaintext" id="newDesc" rows="3" disabled></textarea>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-12 text-center">
                                  <span><b>Imagenes</b></span>
                                </div>
                              </div>

                              <div class="row text-center">
                                <div class="col-4">
                                  <div class="new-image-front">
                                    <i class="fas fa-5x fa-image"></i>
                                  </div>
                                  <br>
                                  <label>Frontal</label>
                                </div>
                                <div class="col-4">
                                  <div class="new-image-right">
                                    <i class="fas fa-5x fa-image"></i>
                                  </div>
                                  <br>
                                  <label>Lat. Der.</label>
                                </div>
                                <div class="col-4">
                                  <div class="new-image-left">
                                    <i class="fas fa-5x fa-image"></i>
                                  </div>
                                  <br>
                                  <label>Lat. Izq.</label>
                                </div>
                              </div>

                              <div class="form-group row mb-5">
                                <div class="col-3 col-sm-3"></div>
                                <div class="col-6 col-sm-6">
                                  <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" onclick="InfoNew( )">
                                    Continuar
                                  </button>
                                </div>
                                <div class="col-3 col-sm-3"></div>
                              </div>

                            </form>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="modal fade" id="conciliarInfoModal" tabindex="-1" role="dialog" aria-labelledby="conciliarInfoLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="conciliarInfoLabel">Detalles del activo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form class="active-inventary-form">

                              <div class="form-group row">
                                <label for="tipoActivo" class="col-sm-4 col-form-label">Tipo de activo</label>
                                <div class="col-sm-8">
                                  <select class="form-control-plaintext iTipoActivo" name="tipoActivo" id="ciTipoActivo" disabled>

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Nombre</label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control-plaintext" id="ciName" placeholder="Ej. Mackbook PRO" disabled>
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
                                  <input type="text" class="form-control-plaintext" id="ciSerie" placeholder="Ej. Nombre" disabled>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="cCosto" class="col-sm-4 col-form-label">Centro de costo</label>
                                <div class="col-sm-8">
                                  <select class="form-control-plaintext" name="cCosto" id="ciCCosto" disabled>
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
                                  <select class="form-control-plaintext iAsignacion" name="asignacion" id="ciAsignacion" disabled>

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
                                  <select class="form-control-plaintext iEmpresa" name="asignacion" id="ciEmpresa" disabled>

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-4 col-form-label">Sucursal</label>
                                <div class="col-sm-8">
                                  <select class="form-control-plaintext iSucursal" name="asignacion" id="ciSucursal" disabled>

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-4 col-form-label">Área</label>
                                <div class="col-sm-8">
                                  <select class="form-control-plaintext iArea" name="asignacion" id="ciArea" disabled>
                                    <option value="1">Sector tecnólogico</option>
                                    <option value="2">Sala de gerencia</option>
                                    <option value="3">Galpón</option>
                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="desc" class="col-sm-4 col-form-label">Descripción</label>
                                <div class="col-sm-8">
                                  <textarea class="form-control-plaintext" id="ciDesc" rows="3" disabled></textarea>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-12 text-center">
                                  <span><b>Imagenes</b></span>
                                </div>
                              </div>

                              <div class="row text-center">
                                <div class="col-4">
                                  <div class="ci-image-front">
                                    <i class="fas fa-5x fa-image"></i>
                                  </div>
                                  <br>
                                  <label>Frontal</label>
                                </div>
                                <div class="col-4">
                                  <div class="ci-image-right">
                                    <i class="fas fa-5x fa-image"></i>
                                  </div>
                                  <br>
                                  <label>Lat. Der.</label>
                                </div>
                                <div class="col-4">
                                  <div class="ci-image-left">
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

                    <div class="modal fade" id="conciliarModal" tabindex="-1" role="dialog" aria-labelledby="conciliarModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="conciliarModalLabel">Pareo de activos</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">

                            <div class="row">
                              <div class="col-12 text-center">
                                <span class="badge badge-success conciliar-porcentaje">--%</span>
                              </div>
                            </div>

                            <div class="row mt-1">
                              <div class="table-responsive">
                                <table class="table table-borderless text-center">
                                  <thead>
                                    <tr>
                                      <th scope="col">Nuevo</th>
                                      <th scope="col">|</th>
                                      <th scope="col">Existente</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="conciliar-tipo-new">NN</td>
                                      <td><b>Tipo</b></td>
                                      <td class="conciliar-tipo-old">NN</td>
                                    </tr>
                                    <tr>
                                      <td class="conciliar-serie-new">NN</td>
                                      <td><b>No. Serie</b></td>
                                      <td class="conciliar-serie-old">NN</td>
                                    </tr>
                                    <tr>
                                      <td class="conciliar-ubicacion-new">NN</td>
                                      <td><b>Ubicación</b></td>
                                      <td class="conciliar-ubicacion-old">NN</td>
                                    </tr>
                                    <tr>
                                      <td class="conciliar-cc-new">NN</td>
                                      <td><b>Centro de costo</b></td>
                                      <td class="conciliar-cc-old">NN</td>
                                    </tr>
                                    <tr>
                                      <td class="conciliar-asignacion-new">NN</td>
                                      <td><b>Asignación</b></td>
                                      <td class="conciliar-asignacion-old">NN</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>

                            <div class="row mt-2">
                              <div class="col-12">
                                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" onclick="ConfirmConciliar( )">Continuar</button>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>

                  </div>

                  <!-- finish -->
                  <div class="inv-update-table w-100 d-none">

                    <div class="card collapsed-card">
                      <div class="card-header text-center card-background-color">
                        <span>Activos con ajustes</span>
                        <span class="badge badge-warning text-white inventary-process-with-count">XX</span>
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse" style="color: white"
                            onclick="setInvInstruccions( 'Selecciona uno de tus activos' )">
                            <i class="fas fa-plus"></i>
                          </button>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-6 float-left align-middle">
                            <span>Total de activos: <b class="inventary-process-with-count">XX</b> </span>
                          </div>
                          <div class="col-6 float-right">

                          </div>
                        </div>

                        <div class="mt-3 table-responsive text-center">
                          <table class="table table-sm table-hover inventary-process-table-content">
                            <thead>
                              <tr>
                                <th scope="col">Activo</th>
                                <th scope="col">Asignación</th>
                                <th scope="col">Cargado</th>
                                <th scope="col"></th>
                              </tr>
                            </thead>
                            <tbody class="inventary-process-table">

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="card collapsed-card">
                      <div class="card-header text-center card-background-color">
                        <span>Activos sin ajustes</span>
                        <span class="badge badge-warning text-white inventary-process-without-count">XX</span>
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse" style="color: white"
                                  onclick="setInvInstruccions( 'Consulta los datos de tus activos act.' )">
                            <i class="fas fa-plus"></i>
                          </button>
                        </div>
                      </div>
                      <div class="card-body">

                        <div class="row">
                          <div class="col-6 float-left align-middle">
                            <span>Total de activos: <b class="inventary-process-without-count">XX</b> </span>
                          </div>
                          <div class="col-6 float-right">
                            <label class="sr-only" for="searchActiveInv">Buscar</label>
                          </div>
                        </div>

                        <div class="mt-3 table-responsive text-center">
                          <table class="table table-hover inventary-process-table2-content">
                            <thead>
                              <tr>
                                <th scope="col">Activo</th>
                                <th scope="col">Asignación</th>
                                <th scope="col">Cargado</th>
                              </tr>
                            </thead>
                            <tbody class="inventary-process-table2">

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="updateModalLabel">Detalles del activo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form class="active-inventary-form">

                              <div class="form-group row">
                                <label for="tipoActivo" class="col-sm-4 col-form-label">Tipo de activo</label>
                                <div class="col-sm-8">
                                  <select class="form-control-plaintext iTipoActivo" name="tipoActivo" id="iTipoActivo" disabled>

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Nombre</label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control-plaintext" id="iName" placeholder="Ej. Mackbook PRO" disabled>
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
                                  <input type="text" class="form-control-plaintext" id="iSerie" placeholder="Ej. Nombre" disabled>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="cCosto" class="col-sm-4 col-form-label">Centro de costo</label>
                                <div class="col-sm-8">
                                  <select class="form-control-plaintext" name="cCosto" id="icCosto" disabled>
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
                                  <select class="form-control-plaintext iAsignacion" name="asignacion" id="iAsignacion" disabled>

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
                                  <select class="form-control-plaintext iEmpresa" name="asignacion" id="iEmpresa" disabled>

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-4 col-form-label">Sucursal</label>
                                <div class="col-sm-8">
                                  <select class="form-control-plaintext iSucursal" name="asignacion" id="iSucursal" disabled>

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-4 col-form-label">Área</label>
                                <div class="col-sm-8">
                                  <select class="form-control-plaintext" name="asignacion" id="iArea" disabled>
                                    <option value="1">Sector tecnólogico</option>
                                    <option value="2">Sala de gerencia</option>
                                    <option value="3">Galpón</option>
                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="desc" class="col-sm-4 col-form-label">Descripción</label>
                                <div class="col-sm-8">
                                  <textarea class="form-control-plaintext" id="iDesc" rows="3" disabled></textarea>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-12 text-center">
                                  <span><b>Imagenes</b></span>
                                </div>
                              </div>

                              <div class="row text-center">
                                <div class="col-4">
                                  <div class="process-image-front">
                                    <i class="fas fa-5x fa-image"></i>
                                  </div>
                                  <br>
                                  <label>Frontal</label>
                                </div>
                                <div class="col-4">
                                  <div class="process-image-right">
                                    <i class="fas fa-5x fa-image"></i>
                                  </div>
                                  <br>
                                  <label>Lat. Der.</label>
                                </div>
                                <div class="col-4">
                                  <div class="process-image-left">
                                    <i class="fas fa-5x fa-image"></i>
                                  </div>
                                  <br>
                                  <label>Lat. Izq.</label>
                                </div>
                              </div>

                              <div class="form-group row mb-5">
                                <div class="col-3 col-sm-3"></div>
                                <div class="col-6 col-sm-6">
                                  <button type="button" class="btn btn-primary btn-block" onclick="ConfirmUpdate( )">
                                    Continuar
                                  </button>
                                </div>
                                <div class="col-3 col-sm-3"></div>
                              </div>

                            </form>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>

                  <!-- finish -->
                  <div class="inv-inv-table w-100 d-none">

                    <div class="card">
                      <div class="card-body">

                        <div class="row mb-3">
                          <div class="col-6 float-left align-middle">
                            <span>Total de activos: <b class="inventary-count">XX</b> </span>
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
                                  <select class="custom-select iTipoActivo" id="invFTipo" name="tipoActivo" onchange="inventaryFiltros( )">
                                    <option value="">Todos</option>
                                  </select>
                                </div>
                              </div>

							                <div class="form-group row">
                                <label for="cCosto" class="col-sm-2 col-form-label">Centro de costo</label>
                                <div class="col-sm-10">
                                  <select class="custom-select" id="invFCC" name="cCosto" onchange="inventaryFiltros( )">
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
                                  <select class="custom-select iEmpresa" id="invFEmpresa" name="asignacion" onchange="inventaryFiltros( )">
                                    <option value="">Todos</option>
                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-2 col-form-label">Sucursal</label>
                                <div class="col-sm-10">
                                  <select class="custom-select iSucursal" id="invFSucursal" name="asignacion" onchange="inventaryFiltros( )">
                                    <option value="">Todas</option>
                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-2 col-form-label">Área</label>
                                <div class="col-sm-10">
                                  <select class="custom-select iArea" id="invFArea" name="asignacion" onchange="inventaryFiltros( )">
                                    <option value="">Todas</option>
                                    <option value="1">Sector tecnólogico</option>
                                    <option value="2">Sala de gerencia</option>
                                    <option value="3">Galpón</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="mt-3 table-responsive text-center">
                          <table class="table table-hover table-inventary-actives-content">
                            <thead>
                              <tr>
                                <th scope="col">Activo</th>
                                <th scope="col">Asignación</th>
                                <th scope="col">Cargado</th>
                              </tr>
                            </thead>
                            <tbody class="table-inventary-actives">

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModallLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="infoModalLabel">Detalles del activo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form class="active-inventary-form">

                              <div class="form-group row">
                                <label for="tipoActivo" class="col-sm-2 col-form-label">Tipo de activo</label>
                                <div class="col-sm-10">
                                  <select class="custom-select iTipoActivo" name="tipoActivo" id="infoTipoActivo" disabled>

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" id="infoName" placeholder="Ej. Mackbook PRO">
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="serie" class="col-sm-2 col-form-label">
                                  No. de serie
                                  <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="right"
                                          style="border-radius: 25px; font-size: 9px !important;"
                                          title="Campo actualizado, valor anterior: MXN56231">
                                    <i class="fas fa-info"></i>
                                  </button>
                                </label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" id="infoSerie" placeholder="Ej. Nombre">
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="cCosto" class="col-sm-2 col-form-label">Centro de costo</label>
                                <div class="col-sm-10">
                                  <select class="custom-select" name="cCosto" id="infocCosto">
                                    <option value="1">Administración</option>
                                    <option value="2">Producción</option>
                                    <option value="3">Marketing</option>
                                    <option value="4">Comercial</option>
                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-2 col-form-label">Asignado a</label>
                                <div class="col-sm-10">
                                  <select class="custom-select iAsignacion" name="asignacion" id="infoAsignacion">

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
                                  <select class="custom-select iEmpresa" name="asignacion" id="infoEmpresa">

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-2 col-form-label">Sucursal</label>
                                <div class="col-sm-10">
                                  <select class="custom-select iSucursal" name="asignacion" id="infoSucursal">

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-2 col-form-label">Área</label>
                                <div class="col-sm-10">
                                  <select class="custom-select" name="asignacion" id="infoArea">

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="desc" class="col-sm-2 col-form-label">Descripción</label>
                                <div class="col-sm-10">
                                  <textarea class="form-control" id="infoDesc" rows="3"></textarea>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-12 text-center">
                                  <span><b>Imagenes</b></span>
                                </div>
                              </div>

                              <div class="row text-center">
                                <div class="col-4">
                                  <div class="info-image-front">
                                    <i class="fas fa-5x fa-image"></i>
                                  </div>
                                  <br>
                                  <label>Frontal</label>
                                </div>
                                <div class="col-4">
                                  <div class="info-image-right">
                                    <i class="fas fa-5x fa-image"></i>
                                  </div>
                                  <br>
                                  <label>Lat. Der.</label>
                                </div>
                                <div class="col-4">
                                  <div class="info-image-left">
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

                </div>

              </div>

            </div>
