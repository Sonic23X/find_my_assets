<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">

  	<meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Find my assets</title>

    <link rel="icon" href="./../favicon.png">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  </head>
  <body>


    <!-- jQuery library -->
    <script src="./../resources/plugins/jquery/jquery-3.5.1.min.js"></script>

    <!-- PopperJS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>


    <script type="text/javascript">

        Swal.fire(
        {
          icon: '<?= $icon ?>',
          title: '<?= $title ?>',
          text: '<?= $text ?>',
          showCancelButton: false,
          confirmButtonColor: '#ffde59',
          confirmButtonText: 'Ok',
          allowOutsideClick: false,
        }).then( ( ) =>
        {
          window.location.href = '<?= $url ?>';
        });

    </script>
  </body>
</html>
