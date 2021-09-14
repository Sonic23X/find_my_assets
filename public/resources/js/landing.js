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

function validarEmail(valor)
{
  const re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
  return re.test( valor );
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

    if(posY > 50)
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

    //validamos cada input
    if ( $( '#nombre' ).val( ).length == 0 ||
         $( '#email' ).val( ).length == 0 ||
         $( '#phone' ).val( ).length == 0 ||
         $( '#comentario' ).val( ).length == 0
    )
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

    //enviamos datos a PHP
    let data =
    {
      nombre: $( '#nombre' ).val( ),
      email: $( '#email' ).val( ),
      phone: $( '#phone' ).val( ),
      comentario: $( '#comentario' ).val( )
    };

    //enviamos datos a PHP
    $.ajax({
      url: $( '#send-contact-email' ).attr( 'action' ),
      type: 'POST',
      dataType: 'json',
      data: data
    })
    .done( response =>
    {
      if ( response.status == 200 )
      {
        imprimir( '¡Hecho!', response.msg , 'success' );
      }
      else
      {
        imprimir( '¡Error!', response.msg, 'error' );
      }

    })
    .fail( ( ) =>
    {

    });


  });

  /* --- Blog --- */

  $.ajax({
    url: 'https://admin.findmy-assets.com/api/blogs',
    //url: 'http://localhost:8000/api/blogs',
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    let blogs = response.blogs;

    if (Object.keys(blogs).length > 0) {
      blogs.forEach(element => {
        let plantilla = 
        `
          <div class="col-sm-4 mb-3">
            <div class="card">
              <img src="${element.image}" alt="image-blog-1" class="img-fluid image-blog">
              <div class="card-body">
                <h5 class="card-title">${element.title}</h5>
                <a href="/blog/${element.id}" class="btn btn-primary">Ver más</a>
              </div>
            </div>
          </div>
        `;
  
        $('#blogs_row').append(plantilla);  
      });
    }
    else
    {
      let plantilla =
      `
        <div class="col">
          <h4 class="text-center">Sin entradas</h4>
        </div>
      `;
      $('#blogs_row').append(plantilla); 
    }
  });

});
