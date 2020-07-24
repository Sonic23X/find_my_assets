<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
					integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

		<style>

			a
			{
				text-decoration: none;
				color: #fff !important;
			}

			.button
			{
				display: inline-block;
				font-weight: 400;
				color: #fff;
				text-align: center;
				vertical-align: middle;
				-webkit-user-select: none;
				-moz-user-select: none;
				-ms-user-select: none;
				user-select: none;
				background: rgb( 255, 222, 89 );
				border-color: rgb( 255, 222, 89 );
				padding: .375rem .75rem;
				font-size: 1rem;
				line-height: 1.5;
				border-radius: .25rem;
				transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
				width: 50%;
			}

			.button:hover
			{
				background: rgb(230, 200, 79);
				border-color: rgb(230, 200, 79);
				color: #fff;
				text-decoration: none;
			}

			.button:focus
			{
				background: rgb(230, 200, 79);
				border-color: rgb(230, 200, 79);
				border-color: rgb(230, 200, 79);
				box-shadow:0 0 0 .2rem rgba(230, 200, 79,.5);
			}

		</style>
	</head>
	<body>

		<div style="width:100%; background:#eee; position:relative; font-family:sans-serif; padding-bottom:40px">
			<center>
				<img style="padding:20px; width:30%" src="<?= base_url( ) ?>/images/landing/logos/without/big-fma.png">
			</center>

			<div style="position:relative; margin:auto; width:600px; background:white; padding:20px">

				<center>
					<h3 style="font-weight:100; color:#999">VERIFIQUE SU DIRECCION DE EMAIL</h3>

					<hr style="border:1px solid #ccc; width:80%">

					<h4 style="font-weight:100; color:#999; padding:0 20px">Para comenzar a usar su cuenta, debe confirmar su email</h4>

					<a href="<?= base_url( '/confirmacion' ) ?>/<?= $llave ?>" target="_blank" class="button mt-3 mb-3 w-50">
						Verifique su mail
					</a>

					<h6 style="font-weight:100; color:#999">Si no reconoce este email, puede ignorar este email y eliminarlo.</h6>
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
