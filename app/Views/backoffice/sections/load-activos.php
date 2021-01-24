            
            <div>
                <div class="row mt-2">
                    <div class="col-12 col-sm-12 col-md-12 title-scanner">
                        <span>Carga masiva de activos</span>
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
                                    <li>El número de activo debe ser único y no debe estar asignado a otro activo del inventario ya cargado.</li>
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
                                    <input type="file" class="custom-file-input" id="excelFile" onChange="changeFile(this)" accept=".xls">
                                    <label class="custom-file-label" for="excelFile" id="excelFileName">Adjuntar plantilla aquí</label>
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
                                    <li>El número de activo debe ser único y no debe estar asignado a otro activo del inventario ya cargado.</li>
                                </ul>
                            </p>
                        </div>
                        <div class="col-sm-3"></div>
                    </div>
                </div>

                <div class="up-result mt-5 d-none">
                    <div class="card collapsed-card">
                        <div class="card-header text-center card-background-color">
                            <span>Activos cargados</span>
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
                                            <th scope="col">No. Activo</th>
                                            <th scope="col">Activo</th>
                                            <th scope="col">Asignación</th>
                                            <th scope="col">Cargado</th>
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
                                            <th scope="col">No. Activo</th>
                                            <th scope="col">#</th>
                                        </tr>
                                    </thead>
                                    <tbody class="up-problems-table-content">

                                    </tbody>
                                </table>
                            </div>
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
                                        <label for="name" class="col-sm-4 col-form-label">Numero de activo</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control-plaintext" id="infoNoActivo" placeholder="Numero de activo" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="tipoActivo" class="col-sm-4 col-form-label">Tipo de activo</label>
                                        <div class="col-sm-8">
                                            <select class="form-control-plaintext iTipoActivo" name="tipoActivo" id="infoTipoActivo" disabled>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="name" class="col-sm-4 col-form-label">Nombre</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control-plaintext" id="infoName" placeholder="Ej. Macbook PRO" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="cCosto" class="col-sm-4 col-form-label">Centro de costo</label>
                                        <div class="col-sm-8">
                                            <select class="form-control-plaintext iCC" name="cCosto" id="infocCosto" disabled>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="asignacion" class="col-sm-4 col-form-label">Asignado a</label>
                                        <div class="col-sm-8">
                                            <select class="form-control-plaintext iAsignacion" name="asignacion" id="infoAsignacion" disabled>
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
                                            <select class="form-control-plaintext iEmpresa" name="asignacion" id="infoEmpresa" disabled>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="asignacion" class="col-sm-4 col-form-label">Sucursal</label>
                                        <div class="col-sm-8">
                                            <select class="form-control-plaintext iSucursal" name="asignacion" id="infoSucursal" disabled>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="asignacion" class="col-sm-4 col-form-label">Área</label>
                                        <div class="col-sm-8">
                                            <select class="form-control-plaintext iArea" name="asignacion" id="infoArea" disabled>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
            

