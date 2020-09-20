
//variables globales
var url = $('#url').val( );
var lon;
var lat;
var isNew = false;
var activeMap;

function dataURLtoFile( dataurl, filename )
{

   var arr = dataurl.split(','),
       mime = arr[0].match(/:(.*?);/)[1],
       bstr = atob(arr[1]),
       n = bstr.length,
       u8arr = new Uint8Array(n);

   while(n--){
       u8arr[n] = bstr.charCodeAt(n);
   }

   return new File([u8arr], filename, {type:mime});
}

/* --- scanner --- */
var wizzardActualView = '.scanner-start';
var wizzardPreviewView = '.scanner-start';

let isMobile =
{
  Android: () =>
  {
    return navigator.userAgent.match(/Android/i);
  },
  BlackBerry: () =>
  {
    return navigator.userAgent.match(/BlackBerry/i);
  },
  iOS: () =>
  {
      return navigator.userAgent.match(/iPhone|iPad|iPod/i);
  },
  Opera: () =>
  {
      return navigator.userAgent.match(/Opera Mini/i);
  },
  Windows: () =>
  {
      return navigator.userAgent.match(/IEMobile/i);
  },
  any: () =>
  {
      return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
  }
};

function activeItem( activar )
{
  //removemos la clase de todos los elementos
  $( '#home' ).removeClass( 'active' );
  $( '#scanner' ).removeClass( 'active' );
  $( '#historico' ).removeClass( 'active' );
  $( '#notify' ).removeClass( 'active' );
  $( '#inventario' ).removeClass( 'active' );

  //la agregamos al elemento
  $( activar ).addClass( 'active' );
}

function imprimir ( titulo, mensaje, tipo )
{
  Swal.fire({
    icon: tipo,
    title: titulo,
    text: mensaje,
    allowOutsideClick: false,
  });
}

function getScannerFormData( )
{
  $( '#tipoActivo' ).html( );
  $( '#asignacion' ).html( );
  $( '#empresas' ).html( );

  $.ajax({
    url: url + '/activos/getFormData',
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let tipos = response.types;

      tipos.forEach( ( tipo, i ) =>
      {

        let typePlantilla =
        `
          <option value="${ tipo.id }">${ tipo.Desc }</option>
        `;

        $( '#tipoActivo' ).append( typePlantilla );

      });

      let usuarios = response.users;

      usuarios.forEach( ( usuario , i ) =>
      {

        let typePlantilla =
        `
          <option value="${ usuario.id_usuario }">${ usuario.nombre + ' ' + usuario.apellidos }</option>
        `;

        $( '#asignacion' ).append( typePlantilla );

      });

      let empresas = response.empresas;

      empresas.forEach( ( empresa , i ) =>
      {

        let typePlantilla =
        `
          <option value="${ empresa.id_empresa }">${ empresa.nombre }</option>
        `;

        $( '#empresas' ).append( typePlantilla );

      });

      let sucursales = response.sucursales;

      sucursales.forEach( ( sucursal , i ) =>
      {

        let typePlantilla =
        `
          <option value="${ sucursal.id }">${ sucursal.Desc }</option>
        `;

        $( '#sucursal' ).append( typePlantilla );

      });

    }
    else
    {
      if ( !isNew )
      {
        imprimir( 'Ups..', response.msg, 'error' );

        $( '#scanner-image-front' ).html( plantilla );
        $( '#scanner-image-right' ).html( plantilla );
        $( '#scanner-image-left' ).html( plantilla );
      }
    }
  });
}

function setCoordenadasMapG( position )
{
  lon = position.coords.longitude;
  lat = position.coords.latitude;

  var globalMap = L.map( 'globalMap' ).setView( [ lat, lon ], 16 );

  L.tileLayer( 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}',
  {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 18,
      id: 'mapbox/streets-v11',
      tileSize: 512,
      zoomOffset: -1,
      accessToken: 'pk.eyJ1IjoiZmluZG15YXNzZXRzIiwiYSI6ImNrZGx5bmU3dTEzbnQycWxqc2wyNjg3MngifQ.P59j7JfBxCpS72-rAyWg0A'
  }).addTo( globalMap );

  L.marker( [ lat, lon ] ).addTo( globalMap )
   .bindPopup( 'Esto es un marcador en el mapa' )
   .openPopup( );
}

function setCoordenadasActiveMap( position )
{
  lon = position.coords.longitude;
  lat = position.coords.latitude;

  activeMap = L.map( 'activeMap' ).setView( [ lat, lon ], 16 );

  L.tileLayer( 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}',
  {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 18,
      id: 'mapbox/streets-v11',
      tileSize: 512,
      zoomOffset: -1,
      accessToken: 'pk.eyJ1IjoiZmluZG15YXNzZXRzIiwiYSI6ImNrZGx5bmU3dTEzbnQycWxqc2wyNjg3MngifQ.P59j7JfBxCpS72-rAyWg0A'
  }).addTo( activeMap );

  L.marker( [ lat, lon ] ).addTo( activeMap )
   .bindPopup( 'Ubicación del activo actualmente' )
   .openPopup( );
}

function scanQR( node )
{
  let reader = new FileReader();

  reader.onload = function()
  {
    node.value = "";
    qrcode.callback = function(res)
    {
      if ( !( res instanceof Error ) )
      {
        let data =
        {
          codigo: res
        };

        //buscamos el codigo en la BDD
        $.ajax({
          url: url + '/activos/search',
          type: 'POST',
          dataType: 'json',
          data: data
        })
        .done( response =>
        {
          if (response.status == 200)
          {
            $( '#scanner-subtipo' ).html( response.tipo.Desc );
            $( '#scanner-nombre' ).html( response.activo.Nom_Activo );
            $( '#scanner-serie' ).html( response.activo.NSerie_Activo );
            $( '#scanner-asignacion' ).html( response.user.nombre + ' ' + response.user.apellidos );
            $( '#vidaUtil' ).val( response.activo.Vida_Activo );
            $( '#empresas' ).val( response.activo.ID_Company );
            $( '#sucursal' ).val( response.activo.ID_Sucursal );
            $( '#area' ).val( response.activo.ID_Area );
            localStorage.setItem( 'codigo', response.activo.ID_Activo );
            isNew = false;

            wizzardPreviewView = wizzardActualView;
            wizzardActualView = '.scanner-status';

            setInsMessage( wizzardActualView, true );

            $( wizzardPreviewView ).addClass( 'd-none' );
            $( wizzardActualView ).removeClass( 'd-none' );
          }
          else
          {
            imprimir( 'Ups..', response.msg, 'error' );
          }
        });
      }
      else
      {
        imprimir( '¡Ups!', 'No se detectó el código QR. Intente de nuevo', 'error' );
      }
    };
    qrcode.decode(reader.result);
  };
  reader.readAsDataURL(node.files[0]);
}

function newScanQR( node )
{
  let reader = new FileReader();

  reader.onload = function()
  {
    node.value = "";
    qrcode.callback = function(res)
    {
      if ( !( res instanceof Error ) )
      {
        localStorage.setItem( 'codigo', res );
        isNew = true;

        wizzardPreviewView = wizzardActualView;
        wizzardActualView = '.scanner-form';

        setInsMessage( wizzardActualView );

        $( wizzardPreviewView ).addClass( 'd-none' );
        $( wizzardActualView ).removeClass( 'd-none' );
      }
      else
      {
        imprimir( '¡Ups!', 'No se detectó el código QR. Intente de nuevo', 'error' );
      }
    };
    qrcode.decode(reader.result);
  };
  reader.readAsDataURL(node.files[0]);
}

function updateFile()
{
  let img = URL.createObjectURL( $( '#fileBar' )[0].files[0] );

  if ( isMobile.any() )
  {
    scanBarCodeQuagga( img );
  }
  else
  {
    $( '#barcode-img' ).attr( 'src', img );
    scanBarCodeZebra( '#barcode-img' );
  }

}

function NewUpdateFile()
{
  let img = URL.createObjectURL( $( '#newFileBar' )[0].files[0] );

  if ( isMobile.any() )
  {
    scanBarCodeQuagga( img, false );
  }
  else
  {
    $( '#new-barcode-img' ).attr( 'src', img );
    scanBarCodeZebra( '#new-barcode-img', false );
  }

}

function scanBarCodeZebra( nodo, search = true )
{
  const codeReader = new ZXing.BrowserBarcodeReader();
  const img = $( nodo )[0].cloneNode(true);

  codeReader.decodeFromImage(img)
            .then(result =>
            {
              if ( search )
              {
                let data =
                {
                  codigo: result.text,
                };

                //buscamos el codigo en la BDD
                $.ajax({
                  url: url + '/activos/search',
                  type: 'POST',
                  dataType: 'json',
                  data: data
                })
                .done( response =>
                {
                  if (response.status == 200)
                  {
                    $( '#scanner-subtipo' ).html( response.tipo.Desc );
                    $( '#scanner-nombre' ).html( response.activo.Nom_Activo );
                    $( '#scanner-serie' ).html( response.activo.NSerie_Activo );
                    $( '#scanner-asignacion' ).html( response.user.nombre + ' ' + response.user.apellidos );
                    $( '#vidaUtil' ).val( response.activo.Vida_Activo );
                    $( '#empresas' ).val( response.activo.ID_Company );
                    $( '#sucursal' ).val( response.activo.ID_Sucursal );
                    $( '#area' ).val( response.activo.ID_Area );
                    localStorage.setItem( 'codigo', response.activo.ID_Activo );
                    isNew = false;

                    wizzardPreviewView = wizzardActualView;
                    wizzardActualView = '.scanner-status';

                    setInsMessage( wizzardActualView, true );

                    $( wizzardPreviewView ).addClass( 'd-none' );
                    $( wizzardActualView ).removeClass( 'd-none' );
                  }
                  else
                  {
                    imprimir( 'Ups..', response.msg, 'error' );
                  }
                });
              }
              else
              {
                localStorage.setItem( 'codigo', result.text );
                isNew = true;

                wizzardPreviewView = wizzardActualView;
                wizzardActualView = '.scanner-form';

                setInsMessage( wizzardActualView );

                $( wizzardPreviewView ).addClass( 'd-none' );
                $( wizzardActualView ).removeClass( 'd-none' );
              }
            })
            .catch(err =>
            {
              imprimir( 'Error', 'No se detectó el código de barras. Intente de nuevo', 'error' );
            });
}

function scanBarCodeQuagga( image, search = true )
{
  Quagga.decodeSingle(
  {
    decoder:
    {
      readers: ['code_128_reader', 'code_39_reader']
    },
    locate: true,
    numOfWorkers: 0,
    inputStream:
    {
      size: 800
    },
    src: image
  },
  function(result)
  {
    if(result.codeResult)
    {

      if ( search )
      {
        let data =
        {
          codigo: result.codeResult.code,
        };

        //buscamos el codigo en la BDD
        $.ajax({
          url: url + '/activos/search',
          type: 'POST',
          dataType: 'json',
          data: data
        })
        .done( response =>
        {
          if (response.status == 200)
          {
            $( '#scanner-subtipo' ).html( response.tipo.Desc );
            $( '#scanner-nombre' ).html( response.activo.Nom_Activo );
            $( '#scanner-serie' ).html( response.activo.NSerie_Activo );
            $( '#scanner-asignacion' ).html( response.user.nombre + ' ' + response.user.apellidos );
            $( '#vidaUtil' ).val( response.activo.Vida_Activo );
            $( '#empresas' ).val( response.activo.ID_Company );
            $( '#sucursal' ).val( response.activo.ID_Sucursal );
            $( '#area' ).val( response.activo.ID_Area );
            localStorage.setItem( 'codigo', response.activo.ID_Activo );
            isNew = false;

            wizzardPreviewView = wizzardActualView;
            wizzardActualView = '.scanner-status';

            setInsMessage( wizzardActualView, true );

            $( wizzardPreviewView ).addClass( 'd-none' );
            $( wizzardActualView ).removeClass( 'd-none' );
          }
          else
          {
            imprimir( 'Ups..', response.msg, 'error' );
          }
        });
      }
      else
      {
        localStorage.setItem( 'codigo', result.codeResult.code );
        isNew = true;

        wizzardPreviewView = wizzardActualView;
        wizzardActualView = '.scanner-form';

        setInsMessage( wizzardActualView );

        $( wizzardPreviewView ).addClass( 'd-none' );
        $( wizzardActualView ).removeClass( 'd-none' );
      }
    } else
    {
      imprimir( 'Error', 'No se detectó el código de barras. Intente de nuevo', 'error' );
    }
  });
}

function setInsMessage( view, update = false )
{
  let message = '';
  switch ( view )
  {
    case '.scanner-start':
      $( '.scan-circle' ).css('background', '#e6c84f');
      $( '.scan-label' ).css('color', '#e6c84f');
      $( '.update-circle' ).css('background', '#6c757d');
      $( '.update-label' ).css('color', '#6c757d');
      $( '.photo-circle' ).css('background', '#6c757d');
      $( '.photo-label' ).css('color', '#6c757d');
      message = 'Selecciona el tipo de etiqueta que tiene el activo';
      break;
    case '.scanner-status':
      $( '.scan-circle' ).css('background', '#6c757d');
      $( '.scan-label' ).css('color', '#6c757d');
      $( '.update-circle' ).css('background', '#e6c84f');
      $( '.update-label' ).css('color', '#e6c84f');
      $( '.photo-circle' ).css('background', '#6c757d');
      $( '.photo-label' ).css('color', '#6c757d');
      message = 'Estás inventariando';
      break;
    case '.scanner-form':
      $( '.scan-circle' ).css('background', '#6c757d');
      $( '.scan-label' ).css('color', '#6c757d');
      $( '.update-circle' ).css('background', '#e6c84f');
      $( '.update-label' ).css('color', '#e6c84f');
      $( '.photo-circle' ).css('background', '#6c757d');
      $( '.photo-label' ).css('color', '#6c757d');
      if ( update )
        message = 'Edita los datos del activo';
      else
        message = 'Ingresa los datos del activo';
      break;
    case '.scanner-geolocation':
      $( '.scan-circle' ).css('background', '#6c757d');
      $( '.scan-label' ).css('color', '#6c757d');
      $( '.update-circle' ).css('background', '#e6c84f');
      $( '.update-label' ).css('color', '#e6c84f');
      $( '.photo-circle' ).css('background', '#6c757d');
      $( '.photo-label' ).css('color', '#6c757d');
      if ( update )
      {
        message = 'Indica el avance en la vida útil del activo';
        $( '#instructions2' ).html( 'Nueva ubicación geográfica del activo' );
        $( '#instructions3' ).html( 'Indica el área donde se encuentra el activo' );
      }
      else
      {
        message = 'Indica el avance en la vida útil del activo';
        $( '#instructions2' ).html( 'Ubicación geográfica del activo' );
        $( '#instructions3' ).html( 'Indica el área donde se encontrará el activo' );
      }
      break;
    case '.scanner-photos':
      $( '.scan-circle' ).css('background', '#6c757d');
      $( '.scan-label' ).css('color', '#6c757d');
      $( '.update-circle' ).css('background', '#6c757d');
      $( '.update-label' ).css('color', '#6c757d');
      $( '.photo-circle' ).css('background', '#e6c84f');
      $( '.photo-label' ).css('color', '#e6c84f');
      message = 'Ingresa las imagenes del activo';
      break;
    case '.scanner-without-scan':
      message = 'Inventario sin escanear';
      break;
    case '.scanner-new':
      message = 'Selecciona y escanea la nueva etiqueta del activo';
      break;
    default:
      message = '-';
      break;
  }

  $( '#instructions' ).html( message );
}

function setImageFront( )
{

  let plantilla =
  `
    <span>Sin imagen</span>
  `;

  $.ajax({
    url: url + `/activos/getImageFront/${ localStorage.getItem( 'codigo' ) }`,
    type: 'GET',
    responseType: 'blob',
    contentType: false,
    processData: false,
  })
  .done( response =>
  {
    if ( response != '' )
    {
      $( '#scanner-image-front' ).html( response );
    }
  });

  $.ajax({
    url: url + `/activos/getImageLeft/${ localStorage.getItem( 'codigo' ) }`,
    type: 'GET',
    contentType: false,
    processData: false,
  })
  .done( response =>
  {
    if ( response != '' )
    {
      $( '#scanner-image-left' ).html( response );
    }
  });

  $.ajax({
    url: url + `/activos/getImageRight/${ localStorage.getItem( 'codigo' ) }`,
    type: 'GET',
    responseType: 'blob',
    contentType: false,
    processData: false,
  })
  .done( response =>
  {
    if ( response != '' )
    {
      $( '#scanner-image-right' ).html( response );
    }
  });

  return true;
}

function putImage( node, type )
{
  let img = URL.createObjectURL( node.files[0] );
  let imagen = new File( [ node.files[ 0 ] ], 'photo.jpg', { type: 'mime' } );

  let plantillaLoad =
  `
    <span>
      <i class="fas fa-cog fa-spin"></i>
      Cargando
    </span>
  `;

  $( `#scanner-image-${ type }` ).html( plantillaLoad );

  let formData = new FormData( );

  formData.set( 'type', type );
  formData.set( 'activo', localStorage.getItem( 'codigo' ) );
  formData.append( 'file', imagen );

  //subir a servidor
  $.ajax({
    url: url + '/activos/setImage',
    type: 'POST',
    dataType: 'json',
    data: formData,
    processData: false,
    contentType: false,
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      imprimir( '¡Hecho!', response.msg, 'success' );

      let plantilla =
      `
        <a href="${ img }" target="_blank">
          <img class="img-fluid" src="${ img }" style="width: 100px; height: 100px;" >
        </a>
      `;

      $( `#scanner-image-${ type }` ).html( plantilla );
    }
    else
    {
      let plantilla =
      `
        <span>Sin imagen</span>
      `;

      $( `#scanner-image-${ type }` ).html( plantilla );

      imprimir( 'Ups...', response.msg, 'error' );
    }
  })
  .fail( ( ) =>
  {
    let plantilla =
    `
      <span>Sin imagen</span>
    `;

    $( `#scanner-image-${ type }` ).html( plantilla );
  });

}

function removeImage( type )
{
  let data =
  {
    type: type,
    codigo: localStorage.getItem( 'codigo' ),
  };

  $.ajax({
    url: url + '/activos/deleteImage',
    type: 'POST',
    dataType: 'json',
    data: data,
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      imprimir( '¡Hecho!', response.msg, 'success' );

      let plantilla =
      `
        <span>Sin imagen</span>
      `;

      $( `#scanner-image-${ type }` ).html( plantilla );
    }
    else
    {
      imprimir( 'Ups...', response.msg, 'error' );
    }
  });

}

function viewImageFront( )
{
  let dataImage = $( '#front-image' ).attr( 'src' );
  let file = dataURLtoFile( dataImage, 'front.jpg' );
  let img = URL.createObjectURL( file );

  window.open( img , '_blank' );
}

function viewImageLeft( )
{
  let dataImage = $( '#left-image' ).attr( 'src' );
  let file = dataURLtoFile( dataImage, 'front.jpg' );
  let img = URL.createObjectURL( file );

  window.open( img , '_blank' );
}

function viewImageRight( )
{
  let dataImage = $( '#right-image' ).attr( 'src' );
  let file = dataURLtoFile( dataImage, 'front.jpg' );
  let img = URL.createObjectURL( file );

  window.open( img , '_blank' );
}

/* --- inventario --- */

function setBackgroundButtons( button )
{
  switch ( button )
  {
    case 'new':
      $( '#inv-new' ).removeClass( 'btn-outline-secondary' );
      $( '#inv-new' ).addClass( 'btn-primary' );

      $( '#inv-update' ).removeClass( 'btn-primary' );
      $( '#inv-update' ).addClass( 'btn-outline-secondary' );

      $( '#inv-inv' ).removeClass( 'btn-primary' );
      $( '#inv-inv' ).addClass( 'btn-outline-secondary' );

      break;
    case 'update':
      $( '#inv-update' ).removeClass( 'btn-outline-secondary' );
      $( '#inv-update' ).addClass( 'btn-primary' );

      $( '#inv-new' ).removeClass( 'btn-primary' );
      $( '#inv-new' ).addClass( 'btn-outline-secondary' );

      $( '#inv-inv' ).removeClass( 'btn-primary' );
      $( '#inv-inv' ).addClass( 'btn-outline-secondary' );
      break;
    case 'inv':
      $( '#inv-inv' ).removeClass( 'btn-outline-secondary' );
      $( '#inv-inv' ).addClass( 'btn-primary' );

      $( '#inv-update' ).removeClass( 'btn-primary' );
      $( '#inv-update' ).addClass( 'btn-outline-secondary' );

      $( '#inv-new' ).removeClass( 'btn-primary' );
      $( '#inv-new' ).addClass( 'btn-outline-secondary' );
      break;
  }
}

function ConfirmUpdate( idActivo )
{
  let id;
  if ( idActivo == null )
    id = localStorage.getItem( 'new-inventary' );
  else
  {
    id = idActivo;
    localStorage.setItem( 'new-inventary', id );
  }

  Swal.fire(
  {
    title: '¡Atención!',
    text: 'Estas a un click de confirmar la actualización de tu activo, esta acción no se podrá deshacer',
    icon: 'warning',
    confirmButtonColor: '#ffde59',
  }).then( result =>
  {
    if ( result.value )
    {
      $.ajax({
        url: url + `/inventario/draftToActivo`,
        type: 'POST',
        dataType: 'json',
        data: { codigo: id }
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          getProcessItems( );

          Swal.fire(
          {
            title: '¡Excelente!',
            text: 'El activo ha sido actualizado exitosamente',
            icon: 'success',
            confirmButtonColor: '#5cb85c',
          })
          .then( result =>
          {
            $( '#updateModal' ).modal( 'hide' );
          });
        }
      });
    }
  });
}

function InfoNew( idActivo = null )
{
  let id;
  if ( idActivo == null )
    id = localStorage.getItem( 'new-inventary' );
  else
  {
    id = idActivo;
    localStorage.setItem( 'new-inventary', id );
  }

  $.ajax({
    url: url + `/inventario/getDraftDetails/${ id }`,
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {

      let activo = response.activo;
      let tipo = response.tipo;
      let usuario = response.user;

      $( '#new-subtipo' ).html( tipo.Desc );
      $( '#new-nombre' ).html( activo.Nom_Activo );
      $( '#new-serie' ).html( activo.NSerie_Activo );
      $( '#new-asignacion' ).html( usuario.nombre + ' ' + usuario.apellidos );

      $( '.inv-news-home' ).addClass( 'd-none' );
      $( '.inv-buttons' ).addClass( 'd-none' );
      $( '.inv-news-confirm' ).removeClass( 'd-none' );

      $( '#inv-instructions' ).html( 'Confirmar alta de activo' );
    }
  });
}

function NewActiveForm( )
{
  let id = localStorage.getItem( 'new-inventary' );
  $.ajax({
    url: url + `/inventario/getDraftBuyDetails/${ id }`,
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activo = response.activo;

      $( '#clp' ).val( activo.Pre_Compra );
      $( '#fechadecompra' ).val( activo.Fec_Compra );
      $( '#fechagarantia' ).val( activo.Fec_Expira );

      if( activo.contabilizar == 0 )
        $( '#contabilizar' ).bootstrapToggle( 'off' );
      else
        $( '#contabilizar' ).bootstrapToggle( 'on' );

      $( '#metodo_depreciacion' ).val( activo.ID_MetDepre );
      $( '#fechastart' ).val( activo.Fec_InicioDepre );
      $( '#vidautilnew' ).val( activo.Vida_Activo );

      $( '.inv-news-confirm' ).addClass( 'd-none' );
      $( '.inv-news-active-new' ).removeClass( 'd-none' );

      $( '#inv-instructions' ).html( 'Ingresa los últimos datos del alta' );
    }
  });
}

function ConfirmNew( )
{

  let id = localStorage.getItem( 'new-inventary' );

  let info =
  {
    codigo: id,
    contabilizar: $( '#contabilizar' ).prop( 'checked' ) ? 1 : 0,
    clp: $( '#clp' ).val( ),
    fecha_compra: $( '#fechadecompra' ).val( ),
    fecha_garantia: $( '#fechagarantia' ).val( ),
    metodo: $( '#metodo_depreciacion' ).val( ),
    fecha_metodo: $( '#fechastart' ).val( ),
    vida_util: $( '#vidautilnew' ).val( ),
  };

  $.ajax({
    url: url + `/inventario/saveDraftBuyDetails`,
    type: 'POST',
    dataType: 'json',
    data: info ,
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      Swal.fire(
      {
        title: '¡Atención!',
        text: 'Estas a un click de confirmar el alta de tu activo, esta acción no se podrá deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffde59',
        cancelButtonColor: '#d9534f',
        cancelButtonText: 'Guardar y seguir luego',
        confirmButtonText: 'Continuar',
      }).then( result =>
      {
        if ( result.value )
        {
          $.ajax({
            url: url + `/inventario/draftToActivo`,
            type: 'POST',
            dataType: 'json',
            data: { 'codigo': id } ,
          })
          .done( response =>
          {
            Swal.fire(
            {
              title: '¡Excelente!',
              text: 'El activo ha sido dado de alta exitosamente',
              icon: 'success',
              confirmButtonColor: '#5cb85c',
            })
            .then( result =>
            {
              getNewItems( );

              $( '.inv-news-active-new' ).addClass( 'd-none' );
              $( '.inv-news-home' ).removeClass( 'd-none' );
              $( '.inv-buttons' ).removeClass( 'd-none' );

              $( '#inv-instructions' ).html( 'Selecciona un activo y confirma su alta' );
            });
          });
        }
        else if ( result.dismiss === Swal.DismissReason.cancel )
        {
          getNewItems( );

          $( '.inv-news-active-new' ).addClass( 'd-none' );
          $( '.inv-news-home' ).removeClass( 'd-none' );
          $( '.inv-buttons' ).removeClass( 'd-none' );

          $( '#inv-instructions' ).html( 'Selecciona un activo y confirma su alta' );
        }
      });
    }
  });
}

function IsConcilar( )
{
  $( '.inv-news-confirm' ).addClass( 'd-none' );
  $( '.inv-news-conciliar' ).removeClass( 'd-none' );
  $( '.inv-step' ).removeClass( 'd-none' );

  $( '.select-circle' ).css('background', '#ffde59');
  $( '.select-label' ).css('color', '#ffde59');
  $( '.confirm-circle' ).css('background', '#6c757d');
  $( '.confirm-label' ).css('color', '#6c757d');

  $( '#inv-instructions' ).html( 'Selecciona el activo a conciliar' );
}

function ConfirmConciliar( )
{
  $( '.inv-news-conciliar' ).addClass( 'd-none' );
  $( '.inv-news-conciliar-confirm' ).removeClass( 'd-none' );

  $( '.select-circle' ).css('background', '#6c757d');
  $( '.select-label' ).css('color', '#6c757d');
  $( '.confirm-circle' ).css('background', '#ffde59');
  $( '.confirm-label' ).css('color', '#ffde59');

  $( '#inv-instructions' ).html( 'Confirma la conciliación' );
}

function ConfirmConciliarMsg( )
{
  Swal.fire(
  {
    title: '¡Excelente!',
    text: 'El activo en tu inventario ha sido actualizado exitosamente',
    icon: 'success',
    confirmButtonColor: '#5cb85c',
  }).then( result =>
  {
    if ( result.value )
    {
      $( '.inv-news-conciliar-confirm' ).addClass( 'd-none' );
      $( '.inv-step' ).addClass( 'd-none' );
      $( '.inv-news-home' ).removeClass( 'd-none' );
      $( '.inv-buttons' ).removeClass( 'd-none' );

      $( '#inv-instructions' ).html( 'Selecciona un activo y confirma su alta' );
    }
  });
}

function setInvInstruccions( text )
{
  $( '#inv-instructions' ).html( text );
}

function getInvFormData( )
{
  $( '.iAsignacion' ).html( );
  $( '.iSucursal' ).html( );
  $( '.iEmpresa' ).html( );
  $( '.iTipoActivo' ).html( );

  $.ajax({
    url: url + '/inventario/getFormData',
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let tipos = response.types;

      tipos.forEach( ( tipo, i ) =>
      {

        let typePlantilla =
        `
          <option value="${ tipo.id }">${ tipo.Desc }</option>
        `;

        $( '.iTipoActivo' ).append( typePlantilla );

      });

      let usuarios = response.users;

      usuarios.forEach( ( usuario , i ) =>
      {

        let typePlantilla =
        `
          <option value="${ usuario.id_usuario }">${ usuario.nombre + ' ' + usuario.apellidos }</option>
        `;

        $( '.iAsignacion' ).append( typePlantilla );

      });

      let empresas = response.empresas;

      empresas.forEach( ( empresa , i ) =>
      {

        let typePlantilla =
        `
          <option value="${ empresa.id_empresa }">${ empresa.nombre }</option>
        `;

        $( '.iEmpresa' ).append( typePlantilla );

      });

      let sucursales = response.sucursales;

      sucursales.forEach( ( sucursal , i ) =>
      {

        let typePlantilla =
        `
          <option value="${ sucursal.id }">${ sucursal.Desc }</option>
        `;

        $( '.iSucursal' ).append( typePlantilla );

      });

    }
    else
    {
      imprimir( 'Ups..', 'Error al obtener la información del servidor', 'error' );
    }
  });
}

function getNewItems( )
{
  $( '.table-new-actives' ).html( '' );

  $.ajax({
    url: url + '/inventario/getItems',
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activos = response.activos;

      activos.forEach( ( activo, i ) =>
      {

        let typePlantilla =
        `
          <tr>
            <td>
              <a class="text-dark text-decoration-none" onClick="getDraftInfoNew( ${ activo.id } )">
                ${ activo.tipo }
                <br>
                ${ activo.nombre }
              </a>
            </td>
            <td class="align-middle">
              ${ activo.usuario }
            </td>
            <td class="align-middle">
              ${ activo.fecha }
            </td>
            <td class="align-middle">
              <button type="button" class="btn btn-primary btn-sm" name="button" onclick="InfoNew( ${ activo.id_activo } )">
                <i class="fas fa-angle-right"></i>
              </button>
            </td>
          </tr>
        `;

        $( '.table-new-actives' ).append( typePlantilla );

      });

      $( '.number-new-actives' ).html( response.number );
    }
    else
    {
      imprimir( 'Ups..', 'Error al obtener la información del servidor', 'error' );
    }
  });
}

function getDraftInfoNew( id )
{
  $.ajax({
    url: url + `/inventario/getDraftInfo/${ id }`,
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activo = response.activo;

      localStorage.setItem( 'new-inventary', activo.ID_Activo );

      $( '#newTipoActivo' ).val( activo.ID_Tipo );
      $( '#newName' ).val( activo.Nom_Activo );
      $( '#newSerie' ).val( activo.NSerie_Activo );
      $( '#newCCosto' ).val( activo.ID_CC );
      $( '#newAsignacion' ).val( activo.User_Inventario );
      $( '#newEmpresa' ).val( activo.ID_Company );
      $( '#newSucursal' ).val( activo.ID_Sucursal );
      $( '#newArea' ).val( activo.ID_Area );
      $( '#newDesc' ).val( activo.Desc_Activo );

      $.ajax({
        url: url + `/activos/getImageFront/${ activo.ID_Activo }`,
        type: 'GET',
        responseType: 'blob',
        contentType: false,
        processData: false,
      })
      .done( response =>
      {
        if ( response != '' )
        {
          $( '.new-image-front' ).html( response );
        }
        else
        {
          $( '.new-image-front' ).html( '<i class="fas fa-5x fa-image"></i>' );
        }
      });

      $.ajax({
        url: url + `/activos/getImageLeft/${ activo.ID_Activo }`,
        type: 'GET',
        contentType: false,
        processData: false,
      })
      .done( response =>
      {
        if ( response != '' )
        {
          $( '.new-image-left' ).html( response );
        }
        else
        {
          $( '.new-image-left' ).html( '<i class="fas fa-5x fa-image"></i>' );
        }
      });

      $.ajax({
        url: url + `/activos/getImageRight/${ activo.ID_Activo }`,
        type: 'GET',
        responseType: 'blob',
        contentType: false,
        processData: false,
      })
      .done( response =>
      {
        if ( response != '' )
        {
          $( '.new-image-right' ).html( response );
        }
        else
        {
          $( '.new-image-right' ).html( '<i class="fas fa-5x fa-image"></i>' );
        }
      });

      $( '#newInvModal' ).modal( 'show' );
    }

  });
}

function getProcessItems()
{
  $( '.inventary-process-table' ).html( '' );

  $.ajax({
    url: url + '/inventario/getProcessItems',
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activos = response.activos;
      let aNuevos = response.nuevos;

      activos.forEach( ( activo, i ) =>
      {

        let typePlantilla =
        `
          <tr>
            <td>
              <a class="text-dark text-decoration-none" onClick="viewProcessInfo( ${ activo.id_activo } )">
                ${ activo.tipo }
                <br>
                ${ activo.nombre }
              </a>
            </td>
            <td class="align-middle">
              ${ activo.usuario }
            </td>
            <td class="align-middle">
              ${ activo.fecha }
            </td>
            <td class="align-middle">
              <button type="button" class="btn btn-primary btn-sm" name="button" onclick="ConfirmUpdate( ${ activo.id_activo } )">
                <i class="fas fa-angle-right"></i>
              </button>
            </td>
          </tr>
        `;

        $( '.inventary-process-table' ).append( typePlantilla );

      });

      aNuevos.forEach( ( activo, i ) =>
      {

        let typePlantilla =
        `
          <tr>
            <td>
              <a class="text-dark text-decoration-none" onClick="viewProcessInfo( ${ activo.id_activo } )">
                ${ activo.tipo }
                <br>
                ${ activo.nombre }
              </a>
            </td>
            <td class="align-middle">
              ${ activo.usuario }
            </td>
            <td class="align-middle">
              ${ activo.fecha }
            </td>
          </tr>
        `;

        $( '.inventary-process-table2' ).append( typePlantilla );

      });

      $( '.inventary-process-with-count' ).html( response.number );
      $( '.inventary-process-without-count' ).html( response.number2 );
    }
    else
    {
      imprimir( 'Ups..', 'Error al obtener la información del servidor', 'error' );
    }
  });
}

function viewProcessInfo( id )
{
  $.ajax({
    url: url + `/inventario/getDraftInfo/${ id }`,
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activo = response.activo;

      localStorage.setItem( 'process-inventary', activo.ID_Activo );

      $( '#newTipoActivo' ).val( activo.ID_Tipo );
      $( '#newName' ).val( activo.Nom_Activo );
      $( '#newSerie' ).val( activo.NSerie_Activo );
      $( '#newCCosto' ).val( activo.ID_CC );
      $( '#newAsignacion' ).val( activo.User_Inventario );
      $( '#newEmpresa' ).val( activo.ID_Company );
      $( '#newSucursal' ).val( activo.ID_Sucursal );
      $( '#newArea' ).val( activo.ID_Area );
      $( '#newDesc' ).val( activo.Desc_Activo );

      $.ajax({
        url: url + `/activos/getImageFront/${ activo.ID_Activo }`,
        type: 'GET',
        responseType: 'blob',
        contentType: false,
        processData: false,
      })
      .done( response =>
      {
        if ( response != '' )
        {
          $( '.new-image-front' ).html( response );
        }
        else
        {
          $( '.new-image-front' ).html( '<i class="fas fa-5x fa-image"></i>' );
        }
      });

      $.ajax({
        url: url + `/activos/getImageLeft/${ activo.ID_Activo }`,
        type: 'GET',
        contentType: false,
        processData: false,
      })
      .done( response =>
      {
        if ( response != '' )
        {
          $( '.new-image-left' ).html( response );
        }
        else
        {
          $( '.new-image-left' ).html( '<i class="fas fa-5x fa-image"></i>' );
        }
      });

      $.ajax({
        url: url + `/activos/getImageRight/${ activo.ID_Activo }`,
        type: 'GET',
        responseType: 'blob',
        contentType: false,
        processData: false,
      })
      .done( response =>
      {
        if ( response != '' )
        {
          $( '.new-image-right' ).html( response );
        }
        else
        {
          $( '.new-image-right' ).html( '<i class="fas fa-5x fa-image"></i>' );
        }
      });

      $( '#newInvModal' ).modal( 'show' );
    }

  });
}

function getInventaryItems( )
{
  $( '.table-inventary-actives' ).html( '' );

  $.ajax({
    url: url + '/inventario/getInventaryItems',
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activos = response.activos;
      let aNuevos = response.nuevos;

      activos.forEach( ( activo, i ) =>
      {

        let typePlantilla =
        `
          <tr>
            <td>
              <a class="text-dark text-decoration-none" onClick="viewInventaryInfo( ${ activo.id_activo } )">
                ${ activo.tipo }
                <br>
                ${ activo.nombre }
              </a>
            </td>
            <td class="align-middle">
              ${ activo.usuario }
            </td>
            <td class="align-middle">
              ${ activo.fecha }
            </td>
          </tr>
        `;

        $( '.table-inventary-actives' ).append( typePlantilla );

      });

      $( '.inventary-count' ).html( response.number );
    }
    else
    {
      imprimir( 'Ups..', 'Error al obtener la información del servidor', 'error' );
    }
  });
}



$(document).ready(function( )
{

  //tooltips
  $( '[data-toggle="tooltip"]' ).tooltip( );

  //localización
  navigator.geolocation.getCurrentPosition( setCoordenadasMapG );

  //formularios
  getScannerFormData( );

  //Vista actual
  let actualView = '.home';

  //funciones para la navegación de la barra
  $( '#inventario' ).click( event =>
  {

    event.preventDefault( );

    //activamos el color
    activeItem( '#inventario' );

    if ( actualView != '.inventary' )
    {
      switch ( actualView )
      {
        case '.scanner':
          $( actualView ).addClass( 'd-none' );
          break;
        case '.home':
          $( actualView ).addClass( 'd-none' );
          break;
      }

      actualView = '.inventary';
      $( actualView ).removeClass( 'd-none' );
    }

  });

  $( '#home' ).click( event =>
  {

    event.preventDefault( );

    //activamos el color
    activeItem( '#home' );

    if ( actualView != '.home' )
    {
      switch ( actualView )
      {
        case '.scanner':
          $( actualView ).addClass( 'd-none' );
          break;
        case '.inventary':
          $( actualView ).addClass( 'd-none' );
          break;
      }

      actualView = '.home';
      $( actualView ).removeClass( 'd-none' );
    }

  });

  $( '#scanner' ).click( event =>
  {

    event.preventDefault( );

    //activamos el color
    activeItem( '#scanner' );

    if ( actualView != '.scanner' )
    {
      switch ( actualView )
      {
        case '.home':
          $( actualView ).addClass( 'd-none' );
          break;
        case '.inventary':
          $( actualView ).addClass( 'd-none' );
          break;
      }

      actualView = '.scanner';
      $( actualView ).removeClass( 'd-none' );
    }

  });

  $( '#historico' ).click( event =>
  {

    event.preventDefault( );

    //activamos el color
    activeItem( '#historico' );



  });

  $( '#notify' ).click( event =>
  {

    event.preventDefault( );

    //activamos el color
    activeItem( '#notify' );



  });

  //grafica de dona - variables
  var donutChartCanvas = $('#donutChart').get(0).getContext('2d');
  var donutData =
  {
    labels:
    [
      'Muebles y útiles',
      'Herramientas',
      'Equipo computacional',
      'Vehiculos',
      'Maquinaria y equipo',
      'Otro',
    ],
    datasets:
    [
      {
        data:
        [
          700, 500, 400, 600, 300, 100
        ],
        backgroundColor:
        [
          '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'
        ],
      }
    ]
  };
  var donutOptions =
  {
    maintainAspectRatio: false,
    responsive: true,
  };

  //creación de la grafica
  var donutChart = new Chart(donutChartCanvas,
  {
    type: 'doughnut',
    data: donutData,
    options: donutOptions
  });

  /* --- scanner - wizzard --- */

  $( '.scan-back' ).click( event =>
  {
    event.preventDefault( );

    setInsMessage( wizzardPreviewView );

    $( wizzardActualView ).addClass( 'd-none' );
    $( wizzardPreviewView ).removeClass( 'd-none' );

    wizzardActualView = wizzardPreviewView;
    wizzardPreviewView = '.menu-start';

  });

  //ready
  $( '#without-scan' ).click( event =>
  {

    wizzardPreviewView = wizzardActualView;
    wizzardActualView = '.scanner-without-scan';

    setInsMessage( wizzardActualView );

    $( wizzardPreviewView ).addClass( 'd-none' );
    $( wizzardActualView ).removeClass( 'd-none' );

  });

  //ready
  $( '#searchCode' ).click( event =>
  {

    let data =
    {
      codigo: $( '#numActivoS1' ).val( )
    };

    //buscamos el codigo en la BDD
    $.ajax({
      url: url + '/activos/search',
      type: 'POST',
      dataType: 'json',
      data: data
    })
    .done( response =>
    {

      if (response.status == 200)
      {
        $( '#scanner-subtipo' ).html( response.tipo.Desc );
        $( '#scanner-nombre' ).html( response.activo.Nom_Activo );
        $( '#scanner-serie' ).html( response.activo.NSerie_Activo );
        $( '#scanner-asignacion' ).html( response.user.nombre + ' ' + response.user.apellidos );
        $( '#vidaUtil' ).val( response.activo.Vida_Activo );
        $( '#empresas' ).val( response.activo.ID_Company );
        $( '#sucursal' ).val( response.activo.ID_Sucursal );
        $( '#area' ).val( response.activo.ID_Area );
        localStorage.setItem( 'codigo', response.activo.ID_Activo );
        isNew = false;

        wizzardPreviewView = wizzardActualView;
        wizzardActualView = '.scanner-status';

        setInsMessage( wizzardActualView, true );

        $( wizzardPreviewView ).addClass( 'd-none' );
        $( wizzardActualView ).removeClass( 'd-none' );
      }
      else
      {
        imprimir( 'Ups..', response.msg, 'error' );
      }
    });
  });

  //ready
  $( '#continueScan' ).click( event =>
  {
    wizzardPreviewView = wizzardActualView;
    wizzardActualView = '.scanner-geolocation';

    setInsMessage( wizzardActualView );

    navigator.geolocation.getCurrentPosition( setCoordenadasActiveMap );

    $( wizzardPreviewView ).addClass( 'd-none' );
    $( wizzardActualView ).removeClass( 'd-none' );
  });

  //ready
  $( '#new-scan' ).click( event =>
  {

    wizzardPreviewView = wizzardActualView;
    wizzardActualView = '.scanner-new';

    setInsMessage( wizzardActualView );

    $( wizzardPreviewView ).addClass( 'd-none' );
    $( wizzardActualView ).removeClass( 'd-none' );

  });

  //ready
  $( '#update1' ).click( event =>
  {
    isNew = false;

    //buscamos el activo en la bdd
    let data =
    {
      codigo: localStorage.getItem( 'codigo' ),
    };

    //buscamos el codigo en la BDD
    $.ajax({
      url: url + '/activos/search',
      type: 'POST',
      dataType: 'json',
      data: data
    })
    .done( response =>
    {
      if (response.status == 200)
      {

        //colocamos la información en el formulario
        $( '#tipoActivo' ).val( response.activo.ID_Tipo );
        $( '#name' ).val( response.activo.Nom_Activo );
        $( '#cCosto' ).val( response.activo.ID_CC );
        $( '#serie' ).val( response.activo.NSerie_Activo );
        $( '#asignacion' ).val( response.activo.User_Inventario );
        $( '#desc' ).val( response.activo.Des_Activo );

        wizzardPreviewView = wizzardActualView;
        wizzardActualView = '.scanner-form';

        setInsMessage( wizzardActualView, true );

        $( wizzardPreviewView ).addClass( 'd-none' );
        $( wizzardActualView ).removeClass( 'd-none' );
      }
      else
      {
        imprimir( 'Ups..', response.msg, 'error' );
      }
    });


  });

  //ready
  $( '#update2' ).click( event =>
  {
    let codigo = $( '#numActivoS2' ).val( );

    if ( codigo.length == 0 )
    {
      imprimir( 'Ups..', 'EL campo del código es obligatorio', 'error' );
      return;
    }

    //validamos que el activo no exista con ese ID
    $.ajax({
      url: url + '/activos/validateNew',
      type: 'POST',
      dataType: 'json',
      data: { codigo: codigo }
    })
    .done( response =>
    {
      if (response.status == 200)
      {
        localStorage.setItem( 'codigo', codigo );
        isNew = true;

        wizzardPreviewView = wizzardActualView;
        wizzardActualView = '.scanner-form';

        setInsMessage( wizzardActualView );

        $( wizzardPreviewView ).addClass( 'd-none' );
        $( wizzardActualView ).removeClass( 'd-none' );
      }
      else
      {
        imprimir( 'Ups..', response.msg, 'error' );
      }
    });

  });

  //ready
  $( '.active-form' ).submit( event =>
  {
    event.preventDefault( );

    //validamos los campos
    if ( $( '#name' ).val( ) == '' ||
         $( '#asignacion' ).val( ) == '' ||
         $( '#desc' ).val( ) == ''
       )
    {
      imprimir( '¡Ups!', 'Todos los campos son obligatorios', 'error' );
      return;
    }

    let data =
    {
      codigo: localStorage.getItem( 'codigo' ),
      tipo: $( '#tipoActivo' ).val( ),
      nombre: $( '#name' ).val( ),
      centro_costo: $( '#cCosto' ).val( ),
      no_serie: $( '#serie' ).val( ),
      asignacion: $( '#asignacion' ).val( ),
      descripcion: $( '#desc' ).val( )
    };

    //ajax here
    let baseurl;

    if ( isNew )
      baseurl = url + '/activos/new';
    else
      baseurl = url + '/activos/updateInfo';

    $.ajax({
      url: baseurl,
      type: 'POST',
      dataType: 'json',
      data: data
    })
    .done( response =>
    {
      if (response.status == 200)
      {
        wizzardPreviewView = wizzardActualView;
        wizzardActualView = '.scanner-geolocation';

        setInsMessage( wizzardActualView );

        navigator.geolocation.getCurrentPosition( setCoordenadasActiveMap );

        $( wizzardPreviewView ).addClass( 'd-none' );
        $( wizzardActualView ).removeClass( 'd-none' );
      }
      else
      {
        imprimir( 'Ups..', response.msg, 'error' );
      }
    });

  });

  $( '#sucursal' ).change(function(event)
  {
    //obtenemos las areas conforme el valor de la sucursal
  });

  //ready
  $( '#nextGeo' ).click( event =>
  {
    event.preventDefault( );

    let gps = `${ lat },${ lon }`;

    //reunimos la informacion en un JSON
    let data =
    {
      codigo: localStorage.getItem( 'codigo' ),
      vida: $( '#vidaUtil' ).val( ),
      empresa: $( '#empresas' ).val( ),
      sucursal: $( '#sucursal' ).val( ),
      area: $( '#area' ).val( ),
      gps: gps,
    };

    //actualizamos el equipo
    $.ajax({
      url: url + '/activos/setGeo',
      type: 'POST',
      dataType: 'json',
      data: data,
    })
    .done( response =>
    {
      if ( response.status == 200 )
      {
        let bool = setImageFront( );

        if ( bool )
        {
          wizzardPreviewView = wizzardActualView;
          wizzardActualView = '.scanner-photos';

          setInsMessage( wizzardActualView );

          $( wizzardPreviewView ).addClass( 'd-none' );
          $( wizzardActualView ).removeClass( 'd-none' );
        }
      }
      else
      {
        imprimir( 'Ups..', response.msg, 'error' );
      }
    });

  });

  //ready
  $( '#scanFinish' ).click( event =>
  {
    event.preventDefault( );

    if (isNew)
      imprimir( '¡Hecho!', 'Activo cargado exitosamente', 'success' );
    else
      imprimir( '¡Hecho!', 'Activo actualizado exitosamente', 'success' );

    //borramos todo el caché
    isNew = false;
    localStorage.removeItem( 'codigo' );
    activeMap.off( );
    activeMap.remove( );
    $( '#tipoActivo' ).val( '' );
    $( '#name' ).val( '' );
    $( '#cCosto' ).val( '' );
    $( '#serie' ).val( '' );
    $( '#asignacion' ).val( '' );
    $( '#desc' ).val( '' );
    $( '#numActivoS1' ).val( '' );
    $( '#numActivoS2' ).val( '' );

    wizzardPreviewView = wizzardActualView;
    wizzardActualView = '.scanner-start';

    setInsMessage( wizzardActualView );

    $( wizzardPreviewView ).addClass( 'd-none' );
    $( wizzardActualView ).removeClass( 'd-none' );

  });

  /* --- inventario --- */

  getInvFormData( );

  $( '#inv-new' ).click( event =>
  {
    event.preventDefault( );

    getNewItems( );

    //colocamos
    setBackgroundButtons( 'new' );

    //mostramos
    $( '.inv-inv-table' ).addClass( 'd-none' );
    $( '.inv-update-table' ).addClass( 'd-none' );

    $( '.inv-news-table' ).removeClass( 'd-none' );
    $( '#inv-instructions' ).html( 'Selecciona un activo y confirma su alta' );
  });

  $( '#inv-update' ).click( event =>
  {
    event.preventDefault( );

    setBackgroundButtons( 'update' );

    getProcessItems( );

    //mostramos
    $( '.inv-news-table' ).addClass( 'd-none' );
    $( '.inv-inv-table' ).addClass( 'd-none' );

    $( '.inv-update-table' ).removeClass( 'd-none' );
    $( '#inv-instructions' ).html( 'Selecciona uno de los grupos de activos actualizados' );
  });

  $( '#inv-inv' ).click( event =>
  {
    event.preventDefault( );

    getInventaryItems( );

    setBackgroundButtons( 'inv' );

    //mostramos
    $( '.inv-news-table' ).addClass( 'd-none' );
    $( '.inv-update-table' ).addClass( 'd-none' );

    $( '.inv-inv-table' ).removeClass( 'd-none' );
    $( '#inv-instructions' ).html( 'Consulta la información online de tus activos' );
  });

  $( '#deleteNewActivo' ).click( event =>
  {
    let id = localStorage.getItem( 'new-inventary' );

    $.ajax({
      url: url + '/inventario/draftDelete',
      type: 'POST',
      dataType: 'json',
      data: { codigo: id },
    })
    .done( response =>
    {
      if ( response.status == 200 )
      {
        getNewItems( );

        Swal.fire(
        {
          title: '¡Listo',
          text: 'El activo ha sido eliminado exitosamente',
          icon: 'success',
          confirmButtonColor: '#5cb85c',
        })
        .then( result =>
        {
          $( '.inv-news-confirm' ).addClass( 'd-none' );
          $( '.inv-news-home' ).removeClass( 'd-none' );
          $( '.inv-buttons' ).removeClass( 'd-none' );
        });
      }
    });
  });

});
