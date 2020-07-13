<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">

  	<meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Find my assets</title>

    <link rel="icon" href="favicon.png">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">


    <!-- Custom CSS -->
    <link rel="stylesheet" href="/resources/css/login.css">

  </head>
  <body class="hidden">

    <!-- loader -->
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

    <!-- Login Form -->
    <div class="container">
      <div class="login-box">

        <form method="post" class="login" action="<?= base_url( '/user-email' ) ?>">
  				<div class="part-1">
  					<h3 class="text-center text-title-login">Hola, ingresa tu e-mail</h3>

            <div class="form-group mt-4">
  						<input type="email" id="email" class="form-control" placeholder="E-mail" name="ingresoEmail" required>
  					</div>

  					<div class="form-group mt-5">
              <button type="button" class="btn btn-primary btn-block next">Continuar</button>
  		      </div>

  					<div class="row mt-4">
  						<div class="col-sm-1"></div>
  						<div class="col-sm-5">
  							<p class="text-center little-text mt-1">¿Sin cuenta?</p>
  						</div>
              <div class="col-sm-5">
  							<p class="text-center little-text mt-1"><a class="ml-2 btn btn-outline-primary" href="<?php echo $ruta; ?>registro">Regístrate</a></p>
  						</div>
  						<div class="col-sm-1"></div>
  					</div>
  				</div>

  				<div class="part-2" style="display: none">
  					<p class="text-center py-3 text-title-login">Ahora tu clave</p>
  					<div class="form-group">
  		        <input type="password" class="form-control" placeholder="Password" name="ingresoPassword" required>
  		      </div>

  					<div class="row">
  		        <div class="col-sm-3"></div>
  		        <div class="col-sm-6">
  		          <button type="submit" class="btn btn-warning text-white btn-block">Acceder</button>
  		        </div>
  						<div class="col-sm-3"></div>
  		      </div>

  					<div class="row mt-4">
  						<div class="col-sm-1"></div>
  						<div class="col-sm-10">
  							<p class="text-center pt-1"><a class="btn btn-outline-warning" href="#modalRecuperarPassword" data-toggle="modal" data-dismiss="modal">¿Olvidó su contraseña?</a></p>
  						</div>
  						<div class="col-sm-1"></div>
  					</div>
  				</div>
  	    </form>

      </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer font-small">

      <div class="footer-copyright text-center py-3">© 2020 Copyright:
        <span> Find my Assets </span>
      </div>

    </footer>

    <!-- jQuery library -->
    <script src="resources/plugins/jquery/jquery-3.5.1.min.js"></script>

    <!-- PopperJS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <!-- Font Awesome 5.13.1 -->
    <script src="resources/plugins/fontawesome/js/all.min.js"></script>

    <!-- Custom JS -->
    <script src="/resources/js/login.js"></script>

  </body>
</html>
