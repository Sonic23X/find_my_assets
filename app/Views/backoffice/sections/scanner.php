
            <div class="scanner">

              <div class="row mt-2">
                <div class="col-1 col-sm-1 col-md-1">
                  <a href="#" class="scanner-back d-none">
                    <i class="fas fa-arrow-left"></i>
                  </a>
                </div>
                <div class="col-11 col-sm-11 col-md-11 title-scanner">
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
                        <div class="step-trigger" onclick="navSteps( 1 )">
                          <span class="bs-stepper-circle scan-circle" style="background: #e6c84f">1</span>
                          <span class="bs-stepper-label scan-label" style="color: #e6c84f">Escanear</span>
                        </div>
                      </div>
                      <div class="line"></div>
                      <div class="step" data-target="#update-part-part">
                        <div class="step-trigger" onclick="navSteps( 2 )">
                          <span class="bs-stepper-circle update-circle">2</span>
                          <span class="bs-stepper-label update-label">Actualizar</span>
                        </div>
                      </div>
                      <div class="line"></div>
                      <div class="step" data-target="#photo-part">
                        <div class="step-trigger">
                          <span class="bs-stepper-circle photo-circle">3</span>
                          <span class="bs-stepper-label photo-label">Fotografiar</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mt-3 p-2 instructions text-center">
                <div class="col-12 col-sm-12 text-center">
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

                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 mt-3 title-scanner">
                    <span>¿Problemas con la etiqueta?</span>
                    <br>
                    <button type="button" class="btn btn-danger mt-1" id="without-scan">Continuar sin escanear</button>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 mt-3 title-scanner">
                    <span>¿El activo es nuevo?</span>
                    <br>
                    <button type="button" class="btn btn-success mt-1" id="new-scan">Ingresarlo</button>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 mt-3 title-scanner">
                    <span>Copiar url</span>
                    <br>
                    <button type="button" class="btn btn-info mt-1" id="copy-url">Copiar</button>
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
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="name" placeholder="Ej. Macbook PRO">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="cCosto" class="col-sm-2 col-form-label">Centro de costo</label>
                    <div class="col-sm-10">
                      <select class="custom-select" name="cCosto" id="cCosto" disabled>
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
                      <select class="custom-select" name="asignacion" id="asignacion">

                      </select>
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
                      <button type="submit" class="btn btn-success btn-block" id="submit-button-form">Continuar</button>
                    </div>
                    <div class="col-3 col-sm-3"></div>
                  </div>

                </form>

              </div>

              <div class="scanner-geolocation mt-3 d-none">

                <div class="row mt-2">
                  <div class="col-1 col-sm-1"></div>
                  <div class="col-10 col-sm-10">
                    <div class="form-group row">
                      <label for="alternativa" class="col-sm-4 col-form-label">
                        Vida útil actual <span id="scanner-vida-util"> </span> :
                        <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="right"
                                style="border-radius: 25px; font-size: 9px !important;"
                                title="Para los activos con vida útil en meses se considera la fecha cuando se ingresa en su inventario">
                          <i class="fas fa-info"></i>
                        </button>
                      </label>
                      <div class="col-sm-6">
                        <input type="number" id="vidaUtil" class="form-control">
                      </div>
                    </div>
                    <div class="form-group row d-none" id="form-u-km">
                      <label for="alternativa" class="col-sm-4 col-form-label-2">
                        <span id="scanner-vida-util-mov"> </span>
                      </label>
                      <div class="col-sm-6">
                        <input type="number" id="vidaUtilActual" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="col-1 col-sm-1"></div>
                </div>

                <div class="row mt-2 p-2 instructions text-center d-none">
                  <div class="col-12 col-sm-12 text-center">
                    <span id="instructions2">Ubicación geográfica del activo</span>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-12 col-sm-12 col-md-12">
                    <div id="activeMap" style="height: 300px;"></div>
                  </div>
                </div>
                <div class="row mt-2 text-center">
                  <div class="col-lg-6 col-sm-6 col-md-6">
                    <span>¿Estas inventariando el activo?</span>
                  </div>
                  <div class="col-lg-6 col-sm-6 col-md-6">
                    <button class="btn btn-block btn-primary" onClick="updateCoordenadas()">Actualizar Ubicación</button>
                  </div>
                </div>

                <div class="row mt-3 p-2 instructions text-center">
                  <div class="col-12 col-sm-12 text-center">
                    <span id="instructions3">Selecciona el tipo de etiqueta que tiene el activo</span>
                  </div>
                </div>

                <div class="row mt-3 text-center d-none">
                  <div class="col-1 col-sm-1"></div>
                  <div class="col-10 col-sm-10">
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Empresa</label>
                      <div class="col-sm-6">
                        <select class="custom-select" name="alternativa" id="empresas">

                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-1 col-sm-1"></div>
                </div>

                <div class="row mt-3 text-center">
                  <div class="col-1 col-sm-1"></div>
                  <div class="col-10 col-sm-10">
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Sucursal</label>
                      <div class="col-sm-6">
                        <select class="custom-select" name="sucursal" id="sucursal">

                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-1 col-sm-1"></div>
                </div>

                <div class="row mt-3 text-center">
                  <div class="col-1 col-sm-1"></div>
                  <div class="col-10 col-sm-10">
                    <div class="form-group row">
                      <label class="col-sm-2 col-form-label">Área</label>
                      <div class="col-sm-6">
                        <select class="custom-select" name="area" id="area">
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-1 col-sm-1"></div>
                </div>

                <div class="form-group row mb-5">
                  <div class="col-3 col-sm-3"></div>
                  <div class="col-6 col-sm-6">
                    <button type="button" class="btn btn-primary btn-block" id="nextGeo">Siguiente</button>
                  </div>
                  <div class="col-3 col-sm-3"></div>
                </div>

              </div>

              <div class="scanner-photos mt-3 d-none">
                <div class="row mt-5 text-center">

                  <div class="col-4 col-sm-4 col-md-4">
                    <label class="image-container">
                      <i class="fas fa-5x fa-camera"></i>
                      <input type=file
                            accept="image/*"
                            capture=environment
                            onChange="putImage(this, 'front')"
                            tabindex=-1/>
                    </label>
                    <p>Frontal</p>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4 vertial-content-align">
                    <div id="scanner-image-front">
                      <span>Sin imagen</span>
                    </div>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4 vertial-content-align">
                    <div class="mt-2">
                      <button type="button" class="btn btn-danger btn-radius" onclick="removeImage( 'front' )">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="row mt-5 text-center">
                  <div class="col-4 col-sm-4 col-md-4">
                    <label class="image-container">
                      <i class="fas fa-5x fa-camera"></i>
                      <input type=file
                            accept="image/*"
                            capture=environment
                            onChange="putImage(this, 'right')"
                            tabindex=-1/>
                    </label>
                    <p>Derecha</p>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4 vertial-content-align">
                    <div id="scanner-image-right">
                      <span>Sin imagen</span>
                    </div>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4 vertial-content-align">
                    <div class="mt-2">
                      <button type="button" class="btn btn-danger btn-radius" onclick="removeImage( 'right' )">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="row mt-5 text-center">
                  <div class="col-4 col-sm-4 col-md-4">
                    <label class="image-container">
                      <i class="fas fa-5x fa-camera"></i>
                      <input type=file
                            accept="image/*"
                            capture=environment
                            onChange="putImage(this, 'left')"
                            tabindex=-1/>
                    </label>
                    <p>Izquierda</p>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4 vertial-content-align">
                    <div id="scanner-image-left">
                      <span>Sin imagen</span>
                    </div>
                  </div>
                  <div class="col-4 col-sm-4 col-md-4 vertial-content-align">
                    <div class="mt-2">
                      <button type="button" class="btn btn-danger btn-radius" onclick="removeImage( 'left' )">
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
                            onChange="newScanQR(this)"
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
                            id="newFileBar"
                            onChange="NewUpdateFile()"
                            tabindex=-1/>
                    </label>
                    <img id="new-barcode-img" class="barcode-img d-none" src="">
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
                        <input class="form-control" type="text" id="numActivoS2" placeholder="Ej. 123456">
                      </div>
                    </div>
                  </div>
                  <div class="col-1 col-sm-1"></div>
                </div>

                <div class="form-group row mb-5">
                  <div class="col-3 col-sm-3"></div>
                  <div class="col-6 col-sm-6">
                    <button type="button" class="btn btn-primary btn-block" id="update2">Continuar</button>
                  </div>
                  <div class="col-3 col-sm-3"></div>
                </div>
              </div>

            </div>
