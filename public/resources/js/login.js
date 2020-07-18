'use strict'

/* --- External functions ---*/

function imprimir ( titulo, mensaje, tipo )
{
  Swal.fire(
  {
    icon: tipo,
    title: titulo,
    text: mensaje,
    allowOutsideClick: false,
  });
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
      imprimir( '¡Error!', '¡El email es obligatorio!', 'error' );
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
        else
        {
          imprimir( '¡Error!', response.msg, 'error' );
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
      imprimir( '¡Error!', '¡La contraseña no es válida!', 'error' );
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
          imprimir( '¡Error!', response.msg, 'error' );
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
        imprimir( '¡Error!', response.msg, 'error' );
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
