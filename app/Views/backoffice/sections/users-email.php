            
            <div class="container-fluid mb-5"></div>

            <div class="row text-center">
                <div class="col-1 col-sm-1">
                    <a href="#" class="inv-back d-none">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="col-10 col-sm-10 col-md-10 text-center title-inv">
                    <b>Usuarios registrados en el sistema</b>
                </div>
                <div class="col-1 col-sm-1"></div>
            </div>

            <div class="row mt-3 p-2 inv-buttons">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <div class="btn-group w-100">
                            <input type="button" class="btn btn-outline-dark" value="Nuevo usuario" data-toggle="modal" data-target="#newUserModal" />
                            <input type="button" class="btn btn-outline-dark d-none" value="Cargar excel" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col">
                    <div class="mt-3 table-responsive text-center">
                        <table class="table table-sm table-hover table-users">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Email</th>
                                </tr>
                            </thead>
                            <tbody class="table-users-items">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="newUserModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newUserModal">Nuevo usuario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="registro">
                                <div class="row">
                                    <div class="col-sm">
                                        <input type="text" class="form-control" placeholder="Nombre" id="nombre" required>
                                    </div>
                                    <div class="col-sm">
                                        <input type="text" class="form-control" placeholder="Apellidos" id="apellidos" required>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-sm">
                                        <input type="email" class="form-control" placeholder="Correo Electrónico" id="email" required>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="password" placeholder="Contraseña" required>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text" id="icon">
                                                            <i class="fas fa-eye"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="justify-content-center">
                                    <div class="btn-group w-100">
                                        <input type="button" class="btn btn-dark" value="Cancelar" />
                                        <input type="submit" class="btn btn-primary" value="Guardar" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModal">Editar usuario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="actualizar">
                                <div class="row">
                                    <div class="col-sm">
                                        <input type="text" class="form-control" placeholder="Nombre" id="eNombre" required>
                                    </div>
                                    <div class="col-sm">
                                        <input type="text" class="form-control" placeholder="Apellidos" id="eApellidos" required>
                                    </div>
                                </div>

                                <div class="row mt-3 mb-3">
                                    <div class="col-sm">
                                        <input type="email" class="form-control" placeholder="Correo Electrónico" id="eEmail" required>
                                    </div>
                                </div>

                                <div class="justify-content-center">
                                    <div class="btn-group w-100">
                                        <input type="button" class="btn btn-dark" value="Cancelar" />
                                        <input type="submit" class="btn btn-primary" value="Actualizar" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
