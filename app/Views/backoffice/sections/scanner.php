
            <div class="scanner d-none">

              <div class="row">
                <div class="col-12 col-sm-12 col-md-12 mt-2 title-scanner">
                  <span>Carga tu inventario en 3 simples pasos</span>
                </div>
              </div>

              <!-- Form Wizzard -->
              <div class="row">
                <!-- Iconos del wizzard -->
                <div class="col-12 col-md-12 col-sm-12">
                  <div class="bs-stepper">
                    <div class="bs-stepper-header" role="tablist">
                      <div class="step" data-target="#scan-part">
                        <div class="step-trigger">
                          <span class="bs-stepper-circle">1</span>
                          <span class="bs-stepper-label">Escanear</span>
                        </div>
                      </div>
                      <div class="line"></div>
                      <div class="step" data-target="#update-part-part">
                        <div class="step-trigger">
                          <span class="bs-stepper-circle">2</span>
                          <span class="bs-stepper-label">Actualizar</span>
                        </div>
                      </div>
                      <div class="line"></div>
                      <div class="step" data-target="#photo-part">
                        <div class="step-trigger">
                          <span class="bs-stepper-circle">3</span>
                          <span class="bs-stepper-label">Fotografiar</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mt-3 p-2 scanner-instructions text-center">
                <div class="col-1 col-sm-1">
                  <a href="#" class="scan-back">
                    <i class="fas fa-arrow-left"></i>
                  </a>
                </div>
                <div class="col-11 col-sm-11 text-center">
                  <span id="instructions">Selecciona el tipo de etiqueta que tiene el activo</span>
                </div>
              </div>

              <div class="scanner-start mt-3">
                <div class="row">
                  <div class="col-6 col-sm-6 col-md-6 options-container">
                    <label class="code-container">
                      <i class="fas fa-5x fa-qrcode"></i>
                      <input type=file
                            accept="image/*"
                            capture=environment
                            onChange="scanQR(this)"
                            tabindex=-1/>
                    </label>
                    <br>
                    Código QR
                  </div>

                  <div class="col-6 col-sm-6 col-md-6 options-container">
                    <label class="code-container">
                      <i class="fas fa-5x fa-barcode"></i>
                      <input type=file
                            accept="image/*"
                            capture=environment
                            id="fileBar"
                            onChange="updateFile(this)"
                            tabindex=-1/>
                    </label>
                    <img id="barcode-img" class="d-none" src="">
                    <br>
                    Código de barras
                  </div>
                </div>

                <div class="row mt-5">
                  <div class="col-12 col-sm-12 col-md-12 mt-3 title-scanner">
                    <span>¿Problemas con la etiqueta?</span>
                    <br>
                    <button type="button" class="btn btn-danger mt-1" id="without-scan">Continuar sin escanear</button>
                  </div>
                </div>

                <div class="row mt-5">
                  <div class="col-12 col-sm-12 col-md-12 mt-3 title-scanner">
                    <span>¿El activo es nuevo?</span>
                    <br>
                    <button type="button" class="btn btn-success mt-1" id="new-scan">Continuar sin escanear</button>
                  </div>
                </div>

                <div class="container-fluid mt-4"> <br> </div>
              </div>

              <div class="scanner-status mt-3 d-none">

                <div class="row mt-5 text-center">
                  <div class="col-12 col-sm-12 col-md-12">
                      <span id="scanner-subtipo"></span> / <span id="scanner-nombre"></span>
                    <br>
                    <span>Serie: <b id="scanner-serie"></b></span>
                  </div>
                </div>

                <div class="row mt-3 text-center">
                  <div class="col-12 col-sm-12 col-md-12">
                    <span>Asignado a <b id="scanner-asignacion"></b></span>
                  </div>
                </div>

                <div class="row mt-5 text-center">
                  <div class="col-6 col-sm-6 col-md-6">
                    <button type="button" class="btn btn-block btn-danger" id="update1">Hay algo mal</button>
                  </div>

                  <div class="col-6 col-sm-6 col-md-6">
                    <button type="button" class="btn btn-block btn-primary" id="continueScan">Continuar</button>
                  </div>
                </div>

              </div>

              <div class="scanner-form mt-3 d-none">
                <form class="active-form">

                  <div class="form-group row">
                    <label for="tipoActivo" class="col-sm-2 col-form-label">Tipo de activo</label>
                    <div class="col-sm-10">
                      <select class="custom-select" name="tipoActivo" id="tipoActivo">
                        <option value="1">Muebles y útiles</option>
                        <option value="2">Herramientas</option>
                        <option value="3">Equipos de computación</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="name" placeholder="Ej. Mackbook PRO">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="cCosto" class="col-sm-2 col-form-label">Centro de costo</label>
                    <div class="col-sm-10">
                      <select class="custom-select" name="cCosto" id="cCosto">
                        <option value="1">Administración</option>
                        <option value="2">Producción</option>
                        <option value="3">Marketing</option>
                        <option value="4">Comercial</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="serie" class="col-sm-2 col-form-label">No. de serie</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="serie" placeholder="Ej. Nombre">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="asignacion" class="col-sm-2 col-form-label">Asignado a</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="asignacion" placeholder="Ej. Nombre">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="desc" class="col-sm-2 col-form-label">Descripción</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" id="desc" rows="3"></textarea>
                    </div>
                  </div>

                  <div class="form-group row mb-5">
                    <div class="col-3 col-sm-3"></div>
                    <div class="col-6 col-sm-6">
                      <button type="submit" class="btn btn-success btn-block">Continuar</button>
                    </div>
                    <div class="col-3 col-sm-3"></div>
                  </div>

                </form>

              </div>

              <div class="scanner-geolocation mt-3 d-none">

                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12">
                    <div id="activeMap" style="height: 300px;"></div>
                  </div>
                </div>

                <div class="row mt-3 p-2 scanner-instructions text-center">
                  <div class="col-12 col-sm-12 text-center">
                    <span id="instructions2">Selecciona el tipo de etiqueta que tiene el activo</span>
                  </div>
                </div>

                <div class="row mt-3 text-center">
                  <div class="col-1 col-sm-1"></div>
                  <div class="col-10 col-sm-10">
                    <div class="form-group row">
                      <label for="alternativa" class="col-sm-2 col-form-label">Selecicona una opción</label>
                      <div class="col-sm-6">
                        <select class="custom-select" name="alternativa" id="alternativa">
                          <option value="1">Sector tecnólogico</option>
                          <option value="2">Sala de gerencia</option>
                          <option value="3">Galpón</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-1 col-sm-1"></div>
                </div>

                <div class="form-group row mb-5">
                  <div class="col-3 col-sm-3"></div>
                  <div class="col-6 col-sm-6">
                    <button type="button" class="btn btn-primary btn-block" id="nextGeo">Buscar</button>
                  </div>
                  <div class="col-3 col-sm-3"></div>
                </div>

              </div>

              <div class="scanner-photos mt-3 d-none">
                <div class="row mt-5 text-center">
                  <div class="col-4 col-sm-4 col-md-4">
                    <i class="fas fa-5x fa-camera"></i>
                    <br>
                    <span>Imagen frontal</span>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4">
                    <i class="fas fa-5x fa-image"></i>
                    <br>
                    <span>Vista previa</span>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4">
                    <div class="photo-button-delete">
                      <button type="button" class="btn btn-danger btn-radius">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="row mt-5 text-center">
                  <div class="col-4 col-sm-4 col-md-4">
                    <i class="fas fa-5x fa-camera"></i>
                    <br>
                    <span>Imagen lateral derecha</span>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4">
                    <i class="fas fa-5x fa-image"></i>
                    <br>
                    <span>Vista previa</span>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4">
                    <div class="photo-button-delete">
                      <button type="button" class="btn btn-danger btn-radius">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="row mt-5 text-center">
                  <div class="col-4 col-sm-4 col-md-4">
                    <i class="fas fa-5x fa-camera"></i>
                    <br>
                    <span>Imagen lateral izquierda</span>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4">
                    <i class="fas fa-5x fa-image"></i>
                    <br>
                    <span>Vista previa</span>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4">
                    <div class="photo-button-delete">
                      <button type="button" class="btn btn-danger btn-radius">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="row mt-5 text-center">
                  <div class="col-4 col-sm-4 col-md-4"></div>
                  <div class="col-4 col-sm-4 col-md-4">
                    <button type="button" class="btn btn-success btn-block" id="scanFinish">Continuar</button>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4"></div>
                </div>

                <div class="container-fluid mt-4"> <br> </div>

              </div>

              <div class="scanner-without-scan mt-3 d-none">
                <div class="row mt-3 text-center">
                  <div class="col-1 col-sm-1"></div>
                  <div class="col-10 col-sm-10">
                    <div class="form-group row">
                      <label for="numActivoS1" class="col-sm-6 col-form-label">Numero de activo</label>
                      <div class="col-sm-6">
                        <input class="form-control" type="text" name="numActivoS1" id="numActivoS1" placeholder="Ej. 123456">
                      </div>
                    </div>
                  </div>
                  <div class="col-1 col-sm-1"></div>
                </div>

                <div class="form-group row mb-5">
                  <div class="col-3 col-sm-3"></div>
                  <div class="col-6 col-sm-6">
                    <button type="button" class="btn btn-primary btn-block" id="searchCode">Buscar</button>
                  </div>
                  <div class="col-3 col-sm-3"></div>
                </div>
              </div>

              <div class="scanner-new mt-3 d-none">
                <div class="row">
                  <div class="col-6 col-sm-6 col-md-6 options-container">
                    <label class="code-container">
                      <i class="fas fa-5x fa-qrcode"></i>
                      <input type=file
                            accept="image/*"
                            capture=environment
                            onChange="scanQR(this)"
                            tabindex=-1/>
                    </label>
                    <br>
                    Código QR
                  </div>

                  <div class="col-6 col-sm-6 col-md-6 options-container">
                    <label class="code-container">
                      <i class="fas fa-5x fa-barcode"></i>
                      <input type=file
                            accept="image/*"
                            capture=environment
                            id="fileBar"
                            onChange="updateFile(this)"
                            tabindex=-1/>
                    </label>
                    <img id="barcode-img" class="d-none" src="">
                    <br>
                    Código de barras
                  </div>
                </div>

                <div class="row mt-5 text-center">
                  <div class="col-1 col-sm-1"></div>
                  <div class="col-10 col-sm-10">
                    <div class="linea">&nbsp;</div>
                    <div class="leyenda">ó</div>
                    <div class="linea">&nbsp;</div>
                  </div>
                  <div class="col-1 col-sm-1"></div>
                </div>

                <div class="row mt-3 text-center">
                  <div class="col-1 col-sm-1"></div>
                  <div class="col-10 col-sm-10">
                    <div class="form-group row">
                      <label for="numActivoS1" class="col-sm-6 col-form-label">Numero de activo</label>
                      <div class="col-sm-6">
                        <input class="form-control" type="text" name="numActivoS1" id="numActivoS2" placeholder="Ej. 123456">
                      </div>
                    </div>
                  </div>
                  <div class="col-1 col-sm-1"></div>
                </div>

                <div class="form-group row mb-5">
                  <div class="col-3 col-sm-3"></div>
                  <div class="col-6 col-sm-6">
                    <button type="button" class="btn btn-primary btn-block" id="update2">Buscar</button>
                  </div>
                  <div class="col-3 col-sm-3"></div>
                </div>
              </div>

            </div>
