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

  $( '.next' ).click((event) =>
	{
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
        url: $( '.login' ).attr( 'action' ),
        type: 'POST',
        dataType: 'json',
        data: data
      })
      .done( response =>
      {
        console.log( reponse );
        
  			$( '.part-1' ).hide( );
  			$( '.part-2' ).show( );
      })
      .fail( ( ) =>
      {
        console.log("error");
      });

		}

	});

  /* -- Formulario de Login Part 2 -- */

  $( '.login' ).submit( (event) =>
  {
    event.preventDefault( );

    imprimir( '¡Error!', 'No pos si', 'error' )
  });

});
