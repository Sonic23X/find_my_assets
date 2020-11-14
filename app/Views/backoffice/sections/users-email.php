            
            <div class="container-fluid mb-5"></div>
            
            <form id="registro" action="<?= base_url( '/usuarios/nuevo' ) ?>">
  				<p class="text-center py-3"><b>Completa tus datos</b></p>

                <div class="row">
                    <div class="col-sm">
                        <input type="text" class="form-control" placeholder="Nombre" id="nombre">
                    </div>
                    <div class="col-sm">
                        <input type="text" class="form-control" placeholder="Apellidos" id="apellidos">
                    </div>
                </div>

  				<div class="row mt-3">
  					<div class="col-sm">
  						<input type="email" class="form-control" placeholder="Correo Electrónico" id="email">
  					</div>
  					<div class="col-sm">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" placeholder="Contraseña">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" id="icon">
                                            <i class="fas fa-eye"></i>
                                        </div>
                                    </div>
                                </div>
    	                    </div>
  			            </div>
  		            </div>
                    <div class="row mt-4">
  					    <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary btn-block">Conseguir url</button>
  					    </div>
  				    </div>
                </div>
          	</form>

            <div class="row mt-5">
                <div class="col">
                    <input type="text" class="form-control" placeholder="url" id="urlcifrada" />
                </div>
            </div>
