'use strict'

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

  /* --- Loader --- */

  $( '.loader' ).fadeOut( 'slow' );
  $( 'body' ).removeClass( 'hidden' );

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

  let pasos = new Swiper( '.pasos-container',
  {
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: 'auto',
    loop: true,
    autoplay:
    {
      delay: 2000,
    },
    coverflowEffect:
    {
      rotate: 10,
      stretch: 0,
      depth: 200,
      modifier: 1,
      slideShadows: false,
    },
    navigation:
    {
      nextEl: '.next-pasos',
      prevEl: '.prev-pasos',
    },
  });

  /* --- Planes contact  --- */

  let planes = new Swiper( '.planes-container',
  {
    initialSlide: 1,
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: 'auto',
    loop: true,
    coverflowEffect:
    {
      rotate: 10,
      stretch: 0,
      depth: 200,
      modifier: 1,
      slideShadows: false,
    },
    navigation:
    {
      nextEl: '.next-planes',
      prevEl: '.prev-planes',
    },
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
