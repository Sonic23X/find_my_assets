
            <div>
            
                <div class="row mt-2">
                    <div class="col-12 col-sm-12 col-md-12 title-scanner">
                        <span>Empresas y locaciones</span>
                    </div>
                </div>

                <div class="row mt-3 p-2 instructions text-center">
                    <div class="col-12 col-sm-12 text-center">
                        <span>Selecciona la empresa que deseas modificiar</span>
                    </div>
                </div>

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="mt-2 mb-2">
                    <span class="font-weight-bold" style="font-size: 20px">Mis empresas</span>
                </div>

                <div class="row">
                    <div class="col-sm-1 col-md-1"></div>
                    <div class="col-sm-10 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive text-center">
                                    <table class="table w-100">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>
                                                    Nombre
                                                </th>
                                                <th>
                                                    Usuarios
                                                </th>
                                                <th>
                                                    Fecha de creación
                                                </th>
                                                <th>
                                                    #
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $count = 0;
                                            foreach($companies as $company)
                                            {
                                            ?>
                                            <tr>
                                                <td id="name_<?= $company->id_empresa ?>">
                                                    <?= $company->nombre ?>
                                                </td>
                                                <td>
                                                    <?= $users[$count] ?>
                                                </td>
                                                <td id="td_<?= $company->id_empresa ?>">
                                                    <?= $company->created_at ?>
                                                </td>
                                                <td>
                                                    <a class="btn btn-primary" data-toggle="collapse"
                                                        href="#collapseCourierRefund<?= $company->id_empresa ?>" role="button" aria-expanded="false"
                                                        aria-controls="collapseCourierRefund<?= $company->id_empresa ?>">
                                                        >
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="collapse" id="collapseCourierRefund<?= $company->id_empresa ?>">
                                                <td colspan="4">
                                                    <div class="form-group row">
                                                        <label class="col-sm col-form-label">Nombre</label>
                                                        <div class="col-sm">
                                                            <input type="text" class="form-control" id="form_name_<?= $company->id_empresa ?>" value="<?= $company->nombre ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm col-form-label mt-5">Logo</label>
                                                        <div class="col-sm mt-5">
                                                            <label class="image-container btn btn-primary">
                                                                Subir imagen
                                                                <input type=file
                                                                        accept="image/*"
                                                                        capture=environment
                                                                        onChange="putImage(this, <?= $company->id_empresa ?>)"
                                                                        tabindex=-1/>
                                                            </label>
                                                        </div>
                                                        <div class="col-sm">
                                                            <div class="img_company_<?= $company->id_empresa ?>">
                                                                <?php 
                                                                    if($company->photo != null)
                                                                    {
                                                                        $dataImage = 'data:image/jpeg;base64,'. base64_encode( $company->photo );
                                                                        echo '<img src="'. $dataImage .'" style="width: 75%">';
                                                                    }
                                                                    else
                                                                        echo "Sin Logo";
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label class="mt-2 mb-2">Período de inventario en curso</label>
                                                    <div class="form-group row">
                                                        <div class="col-sm"></div>
                                                        <label class="col-sm col-form-label">Fecha de inicio</label>
                                                        <div class="col-sm">
                                                            <input type="date" class="form-control" id="form_date_i_<?= $company->id_empresa ?>" value="<?= $fechas[$count]['inicio'] ?>" >
                                                        </div>
                                                        <label class="col-sm col-form-label">Fecha de fin</label>
                                                        <div class="col-sm">
                                                            <input type="date" class="form-control" id="form_date_f_<?= $company->id_empresa ?>" value="<?= $fechas[$count]['fin'] ?>">
                                                        </div>
                                                        <div class="col-sm"></div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm"></div>
                                                        <div class="col-sm">
                                                            <button class="btn btn-primary btn-block" onClick="update(<?= $company->id_empresa ?>)">Actualizar</button>
                                                            <button class="btn btn-danger btn-block mt-3" onClick="finish(<?= $company->id_empresa ?>)">Terminar ciclo</button>
                                                        </div>
                                                        <div class="col-sm"></div>
                                                    </div>
                                                    
                                                    <div class="row mt-5">
                                                        <div class="col-sm-12 text-center">
                                                            <h4>Sucursales</h4>
                                                        </div>
                                                        <div class="col-sm-3"></div>
                                                        <div class="col-sm-6 mt-3">
                                                            <div class="table-responsive text-center">
                                                                <table class="table w-100">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th>
                                                                                Nombre
                                                                            </th>
                                                                            <th>
                                                                                #
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    foreach($sucursales[$count] as $sucursal)
                                                                    {
                                                                    ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?= $sucursal['Desc'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <a href="#" onClick="editSucursal(<?= $sucursal['id'] ?>, '<?= $sucursal['Desc'] ?>')"><i class="fas fa-edit"></i></a>
                                                                                <a href="#" onClick="deleteSucursal(<?= $sucursal['id'] ?>)"><i class="fas fa-times text-danger"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    </tbody>
                                                                </table>
                                                                <button class="mt-3 btn btn-primary" onClick="newSucursal(<?= $company->id_empresa ?>)">Nueva sucursal</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3"></div>
                                                    </div>

                                                    <div class="row mt-5">
                                                        <div class="col-sm-12 text-center">
                                                            <h4>Areas</h4>
                                                        </div>
                                                        <div class="col-sm-3"></div>
                                                        <div class="col-sm-6 mt-3">
                                                            <div class="table-responsive text-center">
                                                                <table class="table w-100">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th>
                                                                                Nombre
                                                                            </th>
                                                                            <th>
                                                                                #
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    foreach($areas[$count] as $area)
                                                                    {
                                                                    ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?= $area['descripcion'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <a href="#" onClick="editArea(<?= $area['id'] ?>, '<?= $area['descripcion'] ?>')"><i class="fas fa-edit"></i></a>
                                                                                <a href="#" onClick="deleteArea(<?= $area['id'] ?>)"><i class="fas fa-times text-danger"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    </tbody>
                                                                </table>
                                                                <button class="mt-3 btn btn-primary" onClick="newArea(<?= $company->id_empresa ?>)">Nueva area</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3"></div>
                                                    </div>

                                                    <div class="row mt-5">
                                                        <div class="col-sm-12 text-center">
                                                            <h4>Tipos de activo</h4>
                                                        </div>
                                                        <div class="col-sm-3"></div>
                                                        <div class="col-sm-6 mt-3">
                                                            <div class="table-responsive text-center">
                                                                <table class="table w-100">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th>
                                                                                Nombre
                                                                            </th>
                                                                            <th>
                                                                                #
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    foreach($tipos[$count] as $tipo)
                                                                    {
                                                                    ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?= $tipo['Desc'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <a href="#" onClick="editTipo(<?= $tipo['id'] ?>, '<?= $tipo['Desc'] ?>')"><i class="fas fa-edit"></i></a>
                                                                                <a href="#" onClick="deleteTipo(<?= $tipo['id'] ?>)"><i class="fas fa-times text-danger"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    </tbody>
                                                                </table>
                                                                <button class="mt-3 btn btn-primary" onClick="newTipo(<?= $company->id_empresa ?>)">Nuevo tipo de activo</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3"></div>
                                                    </div>

                                                    <div class="row mt-5">
                                                        <div class="col-sm-12 text-center">
                                                            <h4>Centro de costos</h4>
                                                        </div>
                                                        <div class="col-sm-3"></div>
                                                        <div class="col-sm-6 mt-3">
                                                            <div class="table-responsive text-center">
                                                                <table class="table w-100">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th>
                                                                                Código
                                                                            </th>
                                                                            <th>
                                                                                Nombre
                                                                            </th>
                                                                            <th>
                                                                                #
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    foreach($ccs[$count] as $cc)
                                                                    {
                                                                    ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?= $cc['Subcuenta'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <?= $cc['Desc'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <a href="#" onClick="editCC(<?= $cc['id'] ?>, '<?= $cc['Desc'] ?>', '<?= $cc['Subcuenta'] ?>')"><i class="fas fa-edit"></i></a>
                                                                                <a href="#" onClick="deleteCC(<?= $cc['id'] ?>)"><i class="fas fa-times text-danger"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    </tbody>
                                                                </table>
                                                                <button class="mt-3 btn btn-primary" onClick="newCC(<?= $company->id_empresa ?>)">Nuevo centro de costos</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;    
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1 col-md-1"></div>
                </div>
                
                <div class="modal fade" tabindex="-1" id="newSucursal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Nueva sucursal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <input type="hidden" id="newSucursalIdEmpresa" />
                                        <input type="text" class="form-control" placeholder="Nombre de la sucursal" id="newSucursalName" />
                                    </div>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-secondary" id="closeNewSucursal">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="saveNewSucursal">Registrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal fade" tabindex="-1" id="editSucursal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Actualizar sucursal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <input type="hidden" id="editSucursalId" />
                                        <input type="text" class="form-control" placeholder="Nombre de la sucursal" id="editSucursalName" />
                                    </div>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-secondary" id="closeEditSucursal">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="saveEditSucursal">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" id="newArea">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Nueva area</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <input type="hidden" id="newAreaIdEmpresa" />
                                        <input type="text" class="form-control" placeholder="Nombre del area" id="newAreaName" />
                                    </div>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-secondary" id="closeNewArea">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="saveNewArea">Registrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" id="editArea">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Actualizar area</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <input type="hidden" id="editAreaId" />
                                        <input type="text" class="form-control" placeholder="Nombre del area" id="editAreaName" />
                                    </div>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-secondary" id="closeEditArea">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="saveEditArea">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" id="newTipo">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Nuevo tipo de activo</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <input type="hidden" id="newTipoIdEmpresa" />
                                        <input type="text" class="form-control" placeholder="Nombre del area" id="newTipoName" />
                                    </div>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-secondary" id="closeNewTipo">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="saveNewTipo">Registrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" id="editTipo">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Actualizar Tipo de activo</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <input type="hidden" id="editTipoId" />
                                        <input type="text" class="form-control" placeholder="Nombre del area" id="editTipoName" />
                                    </div>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-secondary" id="closeEditTipo">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="saveEditTipo">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" id="newCC">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Nuevo centro de costos</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <input type="hidden" id="newCCIdEmpresa" />
                                        <input type="text" class="form-control" placeholder="Nombre del area" id="newCCName" />
                                        <input type="text" class="form-control mt-2" placeholder="Identificador" id="newCCId" />
                                    </div>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-secondary" id="closeNewCC">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="saveNewCC">Registrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" id="editCC">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Actualizar centro de costos</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <input type="hidden" id="editCCId" />
                                        <input type="text" class="form-control" placeholder="Nombre del area" id="editCCName" />
                                        <input type="text" class="form-control mt-2" placeholder="Identificador" id="editCCCode" />
                                    </div>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-secondary" id="closeEditCC">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="saveEditCC">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>