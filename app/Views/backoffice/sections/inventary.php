
            <div class="inventary d-none">

              <div class="row text-center">
                <div class="col-12 col-sm-12 text-center">
                  <b>Confirma y concilia tu inventario</b>
                </div>
              </div>

              <div class="row mt-3 p-2 instructions text-center">
                <div class="col-12 col-sm-12 text-center">
                  <span id="inv-iinstructions">Selecciona uno de los estados de carga</span>
                </div>
              </div>

              <div class="row mt-3 p-2 inv-buttons">
                <div class="col-12">
                  <div class="d-flex justify-content-center">
                    <div class="btn-group" role="group" aria-label="Navegacion">
                      <input type="button" class="btn btn-outline-secondary" value="Nuevos" id="inv-new">
                      <input type="button" class="btn btn-outline-secondary" value="Actualizados" id="inv-update">
                      <input type="button" class="btn btn-outline-secondary" value="Inventario" id="inv-inv">
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-2 inv-news-start">

                <div class="row mt-3">

                  <div class="inv-news-table w-100 d-none">


                  </div>

                  <div class="inv-update-table w-100 d-none">

                    <div class="card collapsed-card">
                      <div class="card-header text-center card-background-color">
                        <span>Activos con ajustes</span>
                        <span class="badge badge-warning text-white">XX</span>
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse" style="color: white">
                            <i class="fas fa-plus"></i>
                          </button>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-6 float-left align-middle">
                            <span>Total de activos: <b>XX</b> </span>
                          </div>
                          <div class="col-6 float-right">
                            <label class="sr-only" for="searchActiveInv">Buscar</label>
                            <div class="input-group mb-2">
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  <i class="fas fa-search"></i>
                                </div>
                              </div>
                              <input type="text" class="form-control" id="searchActiveInv" placeholder="Buscar">
                            </div>
                          </div>
                        </div>

                        <div class="mt-3 table-responsive text-center">
                          <table class="table table-sm table-hover">
                            <thead>
                              <tr>
                                <th scope="col">Activo</th>
                                <th scope="col">Asignación</th>
                                <th scope="col">Cargado</th>
                                <th scope="col"></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <a class="text-dark text-decoration-none" data-toggle="modal" data-target="#updateModal">
                                    [subtipo de activo]
                                    <br>
                                    [tipo de activo]
                                  </a>
                                </td>
                                <td class="align-middle">
                                  [asignado]
                                </td>
                                <td class="align-middle">
                                  dd/mm/aa
                                </td>
                                <td class="align-middle">
                                  <button type="button" class="btn btn-primary btn-sm" name="button" onclick="ConfirmUpdate()">
                                    <i class="fas fa-angle-right"></i>
                                  </button>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a class="text-dark text-decoration-none" data-toggle="modal" data-target="#updateModal">
                                    [subtipo de activo]
                                    <br>
                                    [tipo de activo]
                                  </a>
                                </td>
                                <td class="align-middle">
                                  [asignado]
                                </td>
                                <td class="align-middle">
                                  dd/mm/aa
                                </td>
                                <td class="align-middle">
                                  <button type="button" class="btn btn-primary btn-sm" name="button" onclick="ConfirmUpdate()">
                                    <i class="fas fa-angle-right"></i>
                                  </button>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a class="text-dark text-decoration-none" data-toggle="modal" data-target="#updateModal">
                                    [subtipo de activo]
                                    <br>
                                    [tipo de activo]
                                  </a>
                                </td>
                                <td class="align-middle">
                                  [asignado]
                                </td>
                                <td class="align-middle">
                                  dd/mm/aa
                                </td>
                                <td class="align-middle">
                                  <button type="button" class="btn btn-primary btn-sm" name="button" onclick="ConfirmUpdate()">
                                    <i class="fas fa-angle-right"></i>
                                  </button>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a class="text-dark text-decoration-none" data-toggle="modal" data-target="#updateModal">
                                    [subtipo de activo]
                                    <br>
                                    [tipo de activo]
                                  </a>
                                </td>
                                <td class="align-middle">
                                  [asignado]
                                </td>
                                <td class="align-middle">
                                  dd/mm/aa
                                </td>
                                <td class="align-middle">
                                  <button type="button" class="btn btn-primary btn-sm" name="button" onclick="ConfirmUpdate()">
                                    <i class="fas fa-angle-right"></i>
                                  </button>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <a class="text-dark text-decoration-none" data-toggle="modal" data-target="#updateModal">
                                    [subtipo de activo]
                                    <br>
                                    [tipo de activo]
                                  </a>
                                </td>
                                <td class="align-middle">
                                  [asignado]
                                </td>
                                <td class="align-middle">
                                  dd/mm/aa
                                </td>
                                <td class="align-middle">
                                  <button type="button" class="btn btn-primary btn-sm" name="button" onclick="ConfirmUpdate()">
                                    <i class="fas fa-angle-right"></i>
                                  </button>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="card collapsed-card">
                      <div class="card-header text-center card-background-color">
                        <span>Activos sin ajustes</span>
                        <span class="badge badge-warning text-white">XX</span>
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse" style="color: white">
                            <i class="fas fa-plus"></i>
                          </button>
                        </div>
                      </div>
                      <div class="card-body">

                        <div class="row">
                          <div class="col-6 float-left align-middle">
                            <span>Total de activos: <b>XX</b> </span>
                          </div>
                          <div class="col-5 float-right">
                            <label class="sr-only" for="searchActiveInv">Buscar</label>
                            <div class="input-group mb-2">
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  <i class="fas fa-search"></i>
                                </div>
                              </div>
                              <input type="text" class="form-control" id="searchActiveWiUpdate" placeholder="Buscar">
                            </div>
                          </div>
                        </div>

                        <div class="mt-3 table-responsive text-center">
                          <table class="table table-hover">
                            <thead>
                              <tr>
                                <th scope="col">Activo</th>
                                <th scope="col">Asignación</th>
                                <th scope="col">Cargado</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  [subtipo de activo]
                                  <br>
                                  [tipo de activo]
                                </td>
                                <td class="align-middle">
                                  [asignado]
                                </td>
                                <td class="align-middle">
                                  dd/mm/aa
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  [subtipo de activo]
                                  <br>
                                  [tipo de activo]
                                </td>
                                <td class="align-middle">
                                  [asignado]
                                </td>
                                <td class="align-middle">
                                  dd/mm/aa
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  [subtipo de activo]
                                  <br>
                                  [tipo de activo]
                                </td>
                                <td class="align-middle">
                                  [asignado]
                                </td>
                                <td class="align-middle">
                                  dd/mm/aa
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  [subtipo de activo]
                                  <br>
                                  [tipo de activo]
                                </td>
                                <td class="align-middle">
                                  [asignado]
                                </td>
                                <td class="align-middle">
                                  dd/mm/aa
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  [subtipo de activo]
                                  <br>
                                  [tipo de activo]
                                </td>
                                <td class="align-middle">
                                  [asignado]
                                </td>
                                <td class="align-middle">
                                  dd/mm/aa
                                </td>
                              </tr>
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
                                <label for="tipoActivo" class="col-sm-2 col-form-label">Tipo de activo</label>
                                <div class="col-sm-10">
                                  <select class="custom-select" name="tipoActivo" id="iTipoActivo" disabled>
                                    <option value="">Muebles y utilidades</option>
                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" id="iName" placeholder="Ej. Mackbook PRO">
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
                                  <input type="text" class="form-control" id="iSerie" placeholder="Ej. Nombre">
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="cCosto" class="col-sm-2 col-form-label">Centro de costo</label>
                                <div class="col-sm-10">
                                  <select class="custom-select" name="cCosto" id="icCosto">
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
                                  <select class="custom-select" name="asignacion" id="iAsignacion">

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
                                  <select class="custom-select" name="asignacion" id="iEmpresa">

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-2 col-form-label">Sucursal</label>
                                <div class="col-sm-10">
                                  <select class="custom-select" name="asignacion" id="iSucursal">

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="asignacion" class="col-sm-2 col-form-label">Área</label>
                                <div class="col-sm-10">
                                  <select class="custom-select" name="asignacion" id="iArea">

                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="desc" class="col-sm-2 col-form-label">Descripción</label>
                                <div class="col-sm-10">
                                  <textarea class="form-control" id="iDesc" rows="3"></textarea>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-12 text-center">
                                  <span><b>Imagenes</b></span>
                                </div>
                              </div>

                              <div class="row text-center">
                                <div class="col-4">
                                  <i class="fas fa-5x fa-image"></i>
                                  <br>
                                  <label>Frontal</label>
                                </div>
                                <div class="col-4">
                                  <i class="fas fa-5x fa-image"></i>
                                  <br>
                                  <label>Lat. Der.</label>
                                </div>
                                <div class="col-4">
                                  <i class="fas fa-5x fa-image"></i>
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

                  <div class="inv-inv-table d-none">

                    <div class="row">
                      <div class="col-6 float-left align-middle">
                        <span>Total de activos: <b>XX</b> </span>
                      </div>
                      <div class="col-5 float-right">
                        <label class="sr-only" for="searchActiveInv">Buscar</label>
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                              <i class="fas fa-search"></i>
                            </div>
                          </div>
                          <input type="text" class="form-control" id="searchActiveInv" placeholder="Buscar">
                        </div>
                      </div>
                    </div>

                    <button type="button" class="btn btn-primary btn-block" name="button">Filtros</button>

                    <div class="mt-3 table-responsive">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th scope="col">Activo</th>
                            <th scope="col">Asignación</th>
                            <th scope="col">Cargado</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              [subtipo de activo]
                              <br>
                              [tipo de activo]
                            </td>
                            <td>
                              [asignado]
                            </td>
                            <td>
                              dd/mm/aa
                            </td>
                          </tr>
                          <tr>
                            <td>
                              [subtipo de activo]
                              <br>
                              [tipo de activo]
                            </td>
                            <td>
                              [asignado]
                            </td>
                            <td>
                              dd/mm/aa
                            </td>
                          </tr>
                          <tr>
                            <td>
                              [subtipo de activo]
                              <br>
                              [tipo de activo]
                            </td>
                            <td>
                              [asignado]
                            </td>
                            <td>
                              dd/mm/aa
                            </td>
                          </tr>
                          <tr>
                            <td>
                              [subtipo de activo]
                              <br>
                              [tipo de activo]
                            </td>
                            <td>
                              [asignado]
                            </td>
                            <td>
                              dd/mm/aa
                            </td>
                          </tr>
                          <tr>
                            <td>
                              [subtipo de activo]
                              <br>
                              [tipo de activo]
                            </td>
                            <td>
                              [asignado]
                            </td>
                            <td>
                              dd/mm/aa
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                  </div>

                </div>

              </div>

            </div>
