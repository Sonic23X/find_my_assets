            <div>

                <div class="up-content">
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 title-scanner">
                            <span>Carga masiva de usuarios</span>
                        </div>
                    </div>

                    <!-- Form Wizzard -->
                    <div class="row">
                        <!-- Iconos del wizzard -->
                        <div class="col-12 col-md-12 col-sm-12">
                        <div class="bs-stepper">
                            <div class="bs-stepper-header" role="tablist">
                            <div class="step">
                                <div class="step-trigger" onclick="navSteps( 1 )">
                                <span class="bs-stepper-circle up1-circle" style="background: #e6c84f">1</span>
                                <span class="bs-stepper-label up1-label" style="color: #e6c84f">Descargar</span>
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="step">
                                <div class="step-trigger" onclick="navSteps( 2 )">
                                <span class="bs-stepper-circle up2-circle">2</span>
                                <span class="bs-stepper-label up2-label">Subir</span>
                                </div>
                            </div>
                            
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="row mt-3 p-2 instructions text-center">
                        <div class="col-12 col-sm-12 text-center">
                            <span id="instructions">Obtén y completa la plantilla</span>
                        </div>
                    </div>

                    <div class="up-start">
                        <div class="row mt-3 p-2 text-center">
                            <div class="col-12 col-sm-12">
                                <i class="fas fa-5x fa-download" style="color: #e6c84f"></i>
                            </div>
                        </div>

                        <div class="row mt-3 p-2 text-center">
                            <div class="col-12 col-sm-12">
                                <a class="btn btn-success" href="javascript:download()">Descargar Aquí</a>
                            </div>
                        </div>

                        <div class="row mt-3 p-2">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <p>
                                    <b>Recuerda:</b>
                                    <ul>
                                        <li>No debes modificar el formato del archivo descargado.</li>
                                        <li>Mantén el orden de las columnas expuestas.</li>
                                        <li>Sigue el formato de las celdas de planilla.</li>
                                    </ul>
                                </p>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>

                    </div>

                    <div class="up-load d-none">
                        <div class="row mt-3 p-2 text-center">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="excelFile" onChange="changeFile(this)" accept=".xls,.xlsx">
                                        <label class="custom-file-label" for="excelFile" id="excelFileName">Adjuntar plantilla aquí</label>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="checkbox" value="" id="emailCheck">
                                        <label class="form-check-label" for="emailCheck">
                                            ¿Enviar email a los usuarios?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>

                        <div class="row mt-3 p-2">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <p>
                                    <b>Recuerda:</b>
                                    <ul>
                                        <li>No debes modificar el formato del archivo descargado.</li>
                                        <li>Mantén el orden de las columnas expuestas.</li>
                                        <li>Sigue el formato de las celdas de planilla.</li>
                                    </ul>
                                </p>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>
                    </div>

                    <div class="up-result mt-5 d-none">
                        <div class="card collapsed-card">
                            <div class="card-header text-center card-background-color">
                                <span>Usuarios cargados</span>
                                <span class="badge badge-warning text-white up-ready">XX</span>
                                <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" style="color: white">
                                    <i class="fas fa-plus"></i>
                                </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mt-3 table-responsive text-center up-ready-table-div">
                                    <table class="table table-hover up-ready-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Correo Electronico</th>
                                            </tr>
                                        </thead>
                                        <tbody class="up-ready-table-content">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card collapsed-card">
                            <div class="card-header text-center card-background-color">
                                <span>Problemas encontrados</span>
                                <span class="badge badge-warning text-white up-problems">XX</span>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="color: white">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mt-3 table-responsive text-center">
                                    <table class="table table-hover up-problems-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Problematica</th>
                                                <th scope="col">Linea</th>
                                            </tr>
                                        </thead>
                                        <tbody class="up-problems-table-content">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="up-loading d-none">
                    <div class="loader">
                        <div class="loadingio-spinner-spinner-65dox1ras4p">
                            <div class="ldio-jsxn2qe688r">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


