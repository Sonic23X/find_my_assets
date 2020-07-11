
/* --- External functions ---*/

function imprimir ( titulo, mensaje, tipo )
{
  Swal.fire({
    icon: tipo,
    title: titulo,
    text: mensaje,
    allowOutsideClick: false,
  });
}


$(document).ready(() =>
{

  /* --- Navbar background animate --- */

  $(window).scroll(() =>
  {

    var posY = window.pageYOffset;

    if(posY > 20)
      $( '.navbar' ).attr('style', 'background: #343a40 !important');
    else
  		$( '.navbar' ).attr('style', 'background: transparent !important');

  });

  /* --- Pasos contact  --- */

  $( '.steps-carousel' ).owlCarousel(
  {
    loop: false,
    margin: 30,
    nav: false,
    responsiveClass: true,
    responsive:
    {
      0:
      {
        items: 1,
      },
      768:
      {
        items: 2,
      },
      1024:
      {
        items: 3,
      },
      1366:
      {
        items: 4,
        mouseDrag: false,
        touchDrag: false,
      }
    }
  });

  /* --- Planes contact  --- */

  $( '.plans-carousel' ).owlCarousel(
  {
    loop: false,
    margin: 30,
    nav: false,
    responsiveClass: true,
    responsive:
    {
      0:
      {
        items: 1,
      },
      768:
      {
        items: 2,
      },
      1024:
      {
        items: 2,
      },
      1366:
      {
        items: 3,
        mouseDrag: false,
        touchDrag: false,
      }
    }
  });

  /* --- Email contact  --- */

  $( '#send-contact-email' ).submit( (event) =>
  {
    //caneclamos cualquier acto de envio
    event.preventDefault();

    //enviamos datos a PHP
    imprimir( 'Error', 'Error al enviar el correo', 'error' );

  });



});
