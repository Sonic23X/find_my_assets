'use strict'

/* --- External functions ---*/

function imprimir ( titulo, mensaje, tipo, isLogin = false )
{
  if ( !isLogin )
  {
    Swal.fire(
    {
      icon: tipo,
      title: titulo,
      text: mensaje,
      allowOutsideClick: false,
    });
  }
  else
  {
    Swal.fire(
    {
      icon: tipo,
      title: titulo,
      text: mensaje,
      showCancelButton: true,
      confirmButtonColor: '#ffde59',
      cancelButtonColor: '#343a40',
      confirmButtonText: 'Registrarme',
      cancelButtonText: 'Reintentar',
      allowOutsideClick: false,
    }).then( ( ) =>
    {
      window.location.href = $( '#btn-ingreso' ).attr( 'href' );
    });
  }
}

$( document ).ready( () =>
{

  /* --- Loader --- */

  $( '.loader' ).fadeOut('slow/400/fast', () =>
  {
    $( 'body' ).removeClass( 'hidden' );
    $( '.page-footer' ).css( 'bottom', '0' );
  });

  /* -- Formulario de Login Part 1 -- */

  $( '#login1' ).submit( (event) =>
	{

    event.preventDefault( );

		if ( $( '#email' ).val( ) == '' )
		{
      imprimir( '¡Ups!', '¡El email es obligatorio!', 'error' );
		}
		else
		{

      let data =
      {
        email: $( '#email' ).val( ),
      }

      $.ajax({
        url: $( '#login1' ).attr( 'action' ),
        type: 'POST',
        dataType: 'json',
        data: data
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          //cargamos el nombre
          $( '#name' ).html( response.nombre );

          //mostramos la segunda parte
          $( '.part-1' ).hide( );
          $( '.part-2' ).show( );
        }
        else if ( response.status == 401 )
        {
          imprimir( '¡Ups!', response.msg, 'error', true );
        }

      })
      .fail( ( ) =>
      {

      });

		}

	});

  /* -- Mostrar contraseña -- */

  let visible = false;

  $( '#icon' ).click(function(event)
  {

    if (visible)
    {
      $( this ).html( '<i class="fas fa-eye"></i>' );
      $( '#password' ).attr( 'type', 'password' );
      visible = false;
    }
    else
    {
      $( this ).html( '<i class="fas fa-eye-slash"></i>' );
      $( '#password' ).attr( 'type', 'text' );
      visible = true;
    }

  });

  /* -- Formulario de Login Part 2 -- */

  $( '#login2' ).submit( (event) =>
  {

    event.preventDefault( );

    if ( $( '#password' ).val( ) == '' )
		{
      imprimir( '¡Ups!', '¡La contraseña no es válida!', 'error' );
		}
		else
		{

      let data =
      {
        password: $( '#password' ).val( ),
      }

      $.ajax({
        url: $( '#login2' ).attr( 'action' ),
        type: 'POST',
        dataType: 'json',
        data: data
      })
      .done( response =>
      {
        console.log( response );

        if ( response.status != 200 )
        {
          imprimir( '¡Ups!', response.msg, 'error' );
        }
        else
        {
          window.location.href = response.url;
        }

      })
      .fail( ( ) =>
      {

      });

		}

  });

  /* -- Formulario de Recover passoword-- */

  $( '#recover' ).submit( (event) =>
  {

    event.preventDefault( );

    let data =
    {
      email: $( '#recoverEmail' ).val( ),
    }

    $.ajax({
      url: $( '#recover' ).attr( 'action' ),
      type: 'POST',
      dataType: 'json',
      data: data
    })
    .done( response =>
    {
      console.log( response );

      if ( response.status != 200 )
      {
        imprimir( '¡Ups!', response.msg, 'error' );
      }
      else
      {
        imprimir( '¡Hecho!', response.msg, 'success' );
      }

    })
    .fail( ( ) =>
    {

    });


  });

});
