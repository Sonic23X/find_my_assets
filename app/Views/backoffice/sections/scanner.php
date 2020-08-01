
            <div class="scanner">

              <!-- Form Wizzard -->
              <div class="row">
                <div class="col-12 col-sm-12 col-md-12 mt-2 title-scanner">
                  <span>Carga tu inventario en 3 simples pasos</span>
                </div>

                <!-- Iconos del wizzard -->
                <div class="">
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                      <span class="round-tab">
                        1
                      </span>
                    </li>
                    <li role="presentation" class="active">
                      <span class="round-tab">
                        2
                      </span>
                    </li>
                    <li role="presentation" class="active">
                      <span class="round-tab">
                        3
                      </span>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="row mt-3 p-2 scanner-instructions">
                <span id="instructions">Selecciona el tipo de etiqueta que tiene el activo</span>
              </div>

              <div class="scanner-start mt-3">
                <div class="row">
                  <div class="col-6 col-sm-6 col-md-6 code-container">
                    <label>
                      <i class="fas fa-5x fa-qrcode"></i>
                      <br>
                      Código QR
                      <input type=file
                            accept="image/*"
                            capture=environment
                            onChange="scanQR(this)"
                            tabindex=-1/>
                    </label>
                  </div>

                  <div class="col-6 col-sm-6 col-md-6 code-container">
                    <label>
                      <i class="fas fa-5x fa-barcode"></i>
                      <br>
                      Código de barras
                      <input type=file
                            accept="image/*"
                            capture=environment
                            id="fileBar"
                            onChange="updateFile(this)"
                            tabindex=-1/>
                    </label>
                    <img id="barcode-img" class="d-none" src="">
                  </div>
                </div>

                <div class="row mt-5">
                  <div class="col-12 col-sm-12 col-md-12 mt-3 title-scanner">
                    <span>¿Problemas con la etiqueta?</span>
                    <br>
                    <button type="button" class="btn btn-danger mt-1">Continuar sin escanear</button>
                  </div>
                </div>

                <div class="row mt-5">
                  <div class="col-12 col-sm-12 col-md-12 mt-3 title-scanner">
                    <span>¿El activo es nuevo?</span>
                    <br>
                    <button type="button" class="btn btn-success mt-1">Continuar sin escanear</button>
                  </div>
                </div>
              </div>

            </div>
