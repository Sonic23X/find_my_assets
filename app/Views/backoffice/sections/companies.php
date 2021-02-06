
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
                

            </div>