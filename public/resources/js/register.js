'use strict'

/* --- External functions ---*/

function imprimir ( titulo, mensaje, tipo, isRegister = false )
{
  if ( !isRegister )
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
      showCancelButton: false,
      confirmButtonColor: '#ffde59',
      confirmButtonText: 'Ok',
      allowOutsideClick: false,
    }).then( ( ) =>
    {
      window.location.href = $( '#home' ).val( );
    });
  }
}

function validarEmail(valor)
{
  const re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
  return re.test( valor );
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

    //validamos los datos
    if ( $( '#nombre' ).val( ).length == 0 ||
         $( '#apellidos' ).val( ).length == 0 ||
         $( '#email' ).val( ).length == 0 ||
         $( '#password' ).val( ).length == 0 )
    {
      imprimir( '¡Ups!', 'Todos los campos son obligatorios' , 'error' );
      return;
    }

    //validamos que el email sea valido
    if ( !validarEmail( $( '#email' ).val( ) ) )
    {
      imprimir( '¡Ups!', 'El email no es valido' , 'error' );
      return;
    }

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
        imprimir( '¡Hecho!', response.msg, 'success', true );

    })
    .fail( ( ) =>
    {

    });

  });



});
