<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Contacto</title>

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
					integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	</head>
	<body>
		<div style="width:100%; background:#eee; position:relative; font-family:sans-serif; padding-bottom:40px">
			<center>
				<img style="padding:20px; width:30%" src="<?= base_url( ) ?>/images/landing/logos/without/big-fma.png">
			</center>

			<div style="position:relative; margin:auto; width:600px; background:white; padding:20px">
				<center>
					<h4 style="font-weight: 50; ">Email de contacto a través del formulario web</h4>
					<hr style="border:1px solid #ccc; width:80%">
					<h6 style="font-weight:100; padding:0 20px">Detalles</h6>
					<table class="table table-borderless text-center">
					  <thead>
					    <tr>
					      <th scope="col">Nombre</th>
					      <td><?= $nombre ?></td>
					    </tr>
					  </thead>
					  <tbody>
					    <tr>
					      <th scope="row">Correo</th>
					      <td><?= $correo ?></td>
					    </tr>
					    <tr>
					      <th scope="row">Teléfono</th>
					      <td><?= $telefono ?></td>
					    </tr>
					    <tr>
					      <th scope="row">Mensaje</th>
								<td><?= $mensaje ?></td>
					    </tr>
					  </tbody>
					</table>
				</center>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
						integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
						integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
		integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

	</body>
</html>
