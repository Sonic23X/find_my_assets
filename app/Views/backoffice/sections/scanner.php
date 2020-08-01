
            <div class="scanner">

              <!-- Form Wizzard -->
              <div class="row">
                <div class="col-12 col-sm-12 col-md-12 mt-2 title-scanner">
                  <span>Carga tu inventario en 3 simples pasos</span>
                </div>

                <!-- Iconos del wizzard -->
                <div class="wizzard mt-4 mb-4 w-100">
                  <div class="row text-center">
                    <div class="col-4 col-sm-4 col-md-4">
                      <span class="item-nav active">1</span>
                      <br>
                      <span style="margin-top: 10px;">Escanear</span>
                    </div>
                    <div class="col-4 col-sm-4 col-md-4">
                      <span class="item-nav">2</span>
                      <br>
                      <span>Actualizar</span>
                    </div>
                    <div class="col-4 col-sm-4 col-md-4">
                      <span class="item-nav">3</span>
                      <br>
                      <span>Fotografiar</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mt-3 p-2 scanner-instructions text-center">
                <!--<span id="instructions">Selecciona el tipo de etiqueta que tiene el activo</span>-->
                <!--<span id="instructions">Estás inventariando</span>-->
                <!--<span id="instructions">Edita los datos del activo</span>-->
                <span id="instructions">Nueva ubicación del activo</span>
              </div>

              <div class="scanner-start mt-3 d-none">
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

              <div class="scanner-status mt-3 d-none">

                <div class="row mt-5 text-center">
                  <div class="col-12 col-sm-12 col-md-12">
                    <span>[ Subtipo de activo ] </span> / <span>[ Nombre de activo ]</span>
                    <br>
                    <span>Serie: [ Numero de serie ] </span>
                  </div>
                </div>

                <div class="row mt-3 text-center">
                  <div class="col-12 col-sm-12 col-md-12">
                    <span>Asignado a [ Asignacion ] </span>
                  </div>
                </div>

                <div class="row mt-5 text-center">
                  <div class="col-6 col-sm-6 col-md-6">
                    <button type="button" class="btn btn-block btn-danger">Hay algo mal</button>
                  </div>

                  <div class="col-6 col-sm-6 col-md-6">
                    <button type="button" class="btn btn-block btn-primary">Continuar</button>
                  </div>
                </div>

              </div>

              <div class="scanner-form mt-3 d-none">
                <form class="" method="post">

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
                      <button type="button" class="btn btn-success btn-block">Continuar</button>
                    </div>
                    <div class="col-3 col-sm-3"></div>
                  </div>

                </form>

              </div>

              <div class="scanner-geolocation mt-3 d-none">

                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12">
                    <iframe width="100%" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                    src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=es&amp;q=Les%20Rambles,%201%20Barcelona,%20Spain+(de2.mx)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                  </div>
                </div>

                <div class="row mt-3 p-2 scanner-instructions text-center">
                  <span>Indicael área donde se encuentra el activo</span>
                </div>

                <div class="row mt-3">
                  <div class="form-group row">
                    <label for="alternativa" class="col-sm-2 col-form-label">Selecicona una opción</label>
                    <div class="col-sm-10">
                      <select class="custom-select" name="alternativa" id="alternativa">
                        <option value="1">Sector tecnólogico</option>
                        <option value="2">Sala de gerencia</option>
                        <option value="3">Galpón</option>
                      </select>
                    </div>
                  </div>
                </div>

              </div>

              <div class="scanner-photos mt-3 d-none">

              </div>

              <div class="scanner-without-scan mt-3 d-none">
                <div class="row mt-3 text-center">
                  <div class="form-group row">
                    <label for="numActivoS1" class="col-sm-6 col-form-label">Numero de activo</label>
                    <div class="col-sm-6">
                      <input class="form-control" type="text" name="numActivoS1" id="numActivoS1" placeholder="Ej. 123456">
                    </div>
                  </div>
                </div>

                <div class="form-group row mb-5">
                  <div class="col-3 col-sm-3"></div>
                  <div class="col-6 col-sm-6">
                    <button type="button" class="btn btn-primary btn-block">Buscar</button>
                  </div>
                  <div class="col-3 col-sm-3"></div>
                </div>
              </div>

              <div class="scanner-new mt-3">
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

                <div class="row mt-5 text-center ml-5">
                  <div class="linea">&nbsp;</div>
                  <div class="leyenda">ó</div>
                  <div class="linea">&nbsp;</div>
                </div>

                <div class="row mt-3 text-center">
                  <div class="form-group row">
                    <label for="numActivoS2" class="col-sm-6 col-form-label">Numero de activo</label>
                    <div class="col-sm-6">
                      <input class="form-control" type="text" name="numActivoS2" id="numActivoS2" placeholder="Ej. 123456">
                    </div>
                  </div>
                </div>

                <div class="form-group row mb-5">
                  <div class="col-3 col-sm-3"></div>
                  <div class="col-6 col-sm-6">
                    <button type="button" class="btn btn-primary btn-block">Buscar</button>
                  </div>
                  <div class="col-3 col-sm-3"></div>
                </div>
              </div>

            </div>
