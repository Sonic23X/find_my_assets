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

  /* --- Mostrar contraseña --- */

  let visible = false;

  $( '#icon' ).click( (event) =>
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

  /* --- Registrar usuario --- */

  $( '#registro' ).submit( (event) =>
  {

    event.preventDefault( );

    let data =
    {
      nombre: $( '#nombre' ).val( ),
      apellidos: $( '#apellidos' ).val( ),
      email: $( '#email' ).val( ),
      password: $( '#password' ).val( ),
    }

    $.ajax({
      url: $( '#registro' ).attr( 'action' ),
      type: 'POST',
      dataType: 'json',
      data: data
    })
    .done( response =>
    {

      if ( response.status != 200 )
        imprimir( 'Error', response.msg, 'error' );
      else if ( response.status == 200 )
        window.location.hred = response.url;

    })
    .fail( ( ) =>
    {

    });

  });



});
