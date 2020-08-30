
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
                <div class="col-4">
                  <input type="button" class="btn btn-outline-secondary btn-block btn-sm" value="Nuevos" id="inv-new">
                </div>
                <div class="col-4">
                  <input type="button" class="btn btn-outline-secondary btn-block btn-sm" value="Actualizados" id="inv-update">
                </div>
                <div class="col-4">
                  <input type="button" class="btn btn-outline-secondary btn-block btn-sm" value="Inventario" id="inv-inv">
                </div>
              </div>

              <div class="mt-2 inv-news-start">

                <div class="row mt-3">

                  <div class="inv-news-table d-none">

                    <div class="row">
                      <div class="col-6 float-left align-middle">
                        <span>Total de activos: <b>XX</b> </span>
                      </div>
                      <div class="col-6 float-right">
                        <label class="sr-only" for="searchActiveNew">Buscar</label>
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                              <i class="fas fa-search"></i>
                            </div>
                          </div>
                          <input type="text" class="form-control" id="searchActiveNew" placeholder="Buscar">
                        </div>
                      </div>
                    </div>

                    <div class="table-responsive w-100">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th scope="col"></th>
                            <th scope="col">Activo</th>
                            <th scope="col">Asignación</th>
                            <th scope="col">Cargado</th>
                            <th scope="col"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th>
                              <input type="checkbox" aria-label="Checkbox for following text input">
                            </th>
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
                            <td>
                              <button type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-chevron-right"></i>
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <th>
                              <input type="checkbox" aria-label="Checkbox for following text input">
                            </th>
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
                            <td>
                              <button type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-chevron-right"></i>
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <th>
                              <input type="checkbox" aria-label="Checkbox for following text input">
                            </th>
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
                            <td>
                              <button type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-chevron-right"></i>
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <th>
                              <input type="checkbox" aria-label="Checkbox for following text input">
                            </th>
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
                            <td>
                              <button type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-chevron-right"></i>
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <th>
                              <input type="checkbox" aria-label="Checkbox for following text input">
                            </th>
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
                            <td>
                              <button type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-chevron-right"></i>
                              </button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                  </div>

                  <div class="inv-update-table d-none">

                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col text-center">
                            <span>Activos con ajustes</span>
                            <span class="badge badge-warning text-white">XX</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col text-center">
                            <span>Activos sin ajustes (últimos 10 días)</span>
                            <span class="badge badge-warning text-white">XX</span>
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
