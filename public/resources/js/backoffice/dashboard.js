
//variables globales
var url = $('#url').val( );
var lon;
var lat;
var isNew = false;
var activeMap;
var actualStepScanner = 1;
var actualStepInv = 1;

var newTable = null;
var conciliarTable = null;
var procesoCTable = null;
var procesoWTable = null;
var inventarioTable = null;
var downTable = null;

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

let spanish =
{
  sProcessing: 'Procesando...',
  sLengthMenu: 'Mostrar _MENU_ registros',
  sZeroRecords: 'No se encontraron resultados',
  sEmptyTable: 'Ningún dato disponible en esta tabla',
  sInfo: 'Mostrando _START_ - _END_ de _TOTAL_',
  sInfoEmpty: 'Sin registros',
  sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
  sInfoPostFix: '',
  sSearch: 'Buscar:',
  sUrl: '',
  sInfoThousands: ',',
  sLoadingRecords: 'Cargando...',
  oPaginate:
  {
    sFirst: 'Primero',
    sLast: 'Último',
    sNext: 'Siguiente',
    sPrevious: 'Anterior',
  },
  oAria:
  {
    sSortAscending: ': Activar para ordenar la columna de manera ascendente',
    sSortDescending: ': Activar para ordenar la columna de manera descendente',
  },
};

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
  $( '#down' ).removeClass( 'active' );
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

        window.scroll(0, 0);
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

function navSteps( tipo )
{
  switch ( tipo )
  {
    case 1:
      if ( wizzardActualView != '.scanner-start' && actualStepScanner > 1 )
      {
        if (isNew)
        {
          wizzardPreviewView = wizzardActualView;
          wizzardActualView = '.scanner-start';
          actualStepScanner = 1;
          setInsMessage( wizzardActualView, true );

          $( wizzardPreviewView ).addClass( 'd-none' );
          $( wizzardActualView ).removeClass( 'd-none' );

          window.scroll(0, 0);
        }
        else
        {
          wizzardPreviewView = wizzardActualView;
          wizzardActualView = '.scanner-start';
          actualStepScanner = 1;
          setInsMessage( wizzardActualView );

          $( wizzardPreviewView ).addClass( 'd-none' );
          $( wizzardActualView ).removeClass( 'd-none' );

          window.scroll(0, 0);
        }
      }
      break;
    case 2:
      if ( wizzardActualView != '.scanner-status' && actualStepScanner > 2 )
      {
        if (isNew)
        {
          wizzardPreviewView = wizzardActualView;
          wizzardActualView = '.scanner-status';
          actualStepScanner = 2;
          setInsMessage( wizzardActualView, true );

          $( wizzardPreviewView ).addClass( 'd-none' );
          $( wizzardActualView ).removeClass( 'd-none' );

          window.scroll(0, 0);
        }
        else
        {
          wizzardPreviewView = wizzardActualView;
          wizzardActualView = '.scanner-status';
          actualStepScanner = 2;
          setInsMessage( wizzardActualView );

          $( wizzardPreviewView ).addClass( 'd-none' );
          $( wizzardActualView ).removeClass( 'd-none' );

          window.scroll(0, 0);
        }
      }
      break;
  }
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
var InvActualView = '.inv-news-home';
var InvPreviewView = '';

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
    id = localStorage.getItem( 'process-inventary' );
  else
  {
    id = idActivo;
    localStorage.setItem( 'process-inventary', id );
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

            window.scroll(0, 0);
          });
        }
        else
        {
          Swal.fire(
          {
            title: 'Ups..!',
            text: 'Ha ocurrido un error, intente más tarde',
            icon: 'error',
            confirmButtonColor: '#5cb85c',
          })
          .then( result =>
          {
            $( '#updateModal' ).modal( 'hide' );

            window.scroll(0, 0);
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
      let conciliar = response.concilar;

      $( '#new-subtipo' ).html( tipo.Desc );
      $( '#new-nombre' ).html( activo.Nom_Activo );
      $( '#new-serie' ).html( activo.NSerie_Activo );
      $( '#new-asignacion' ).html( usuario.nombre + ' ' + usuario.apellidos );

      if ( conciliar == 1 )
      {
        $( '.inv-form-conciliar' ).removeClass( 'd-none' );
        $( '.inv-form-continue-info' ).addClass( 'd-none' );
        $( '.inv-back' ).removeClass( 'd-none' );
      }
      else
      {
        $( '.inv-form-continue-info' ).removeClass( 'd-none' );
        $( '.inv-form-conciliar' ).addClass( 'd-none' );
        $( '.inv-back' ).removeClass( 'd-none' );
      }

      InvActualView = '.inv-news-confirm';

      $( '.inv-news-home' ).addClass( 'd-none' );
      $( '.inv-buttons' ).addClass( 'd-none' );
      $( '.inv-news-confirm' ).removeClass( 'd-none' );

      window.scroll(0, 0);

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

      InvActualView = '.inv-news-active-new';
      window.scroll(0, 0);

      $( '#inv-instructions' ).html( 'Ingresa los últimos datos del alta' );
    }
    else
    {

    }
  });
}

function setFactura( node )
{
  let formData = new FormData( );

  formData.set( 'activo', localStorage.getItem( 'new-inventary' ) );
  formData.append( 'file', node.files[0] );

  //subir a servidor
  $.ajax({
    url: url + '/inventario/setFactura',
    type: 'POST',
    dataType: 'json',
    data: formData,
    processData: false,
    contentType: false,
  })
  .done( response =>
  {
    if ( response.status == 200 )
      imprimir( 'Hecho', response.msg, 'success' );
    else
      imprimir( 'Ups..', response.msg, 'error' );
  })
  .fail( ( ) =>
  {
    imprimir( 'Ups..', 'Error al conectar con el servidor', 'error' );
  });
}

function setGarantia( node )
{
  let formData = new FormData( );

  formData.set( 'activo', localStorage.getItem( 'new-inventary' ) );
  formData.append( 'file', node.files[0] );

  //subir a servidor
  $.ajax({
    url: url + '/inventario/setGarantia',
    type: 'POST',
    dataType: 'json',
    data: formData,
    processData: false,
    contentType: false,
  })
  .done( response =>
  {
    if ( response.status == 200 )
      imprimir( 'Hecho', response.msg, 'success' );
    else
      imprimir( 'Ups..', response.msg, 'error' );
  })
  .fail( ( ) =>
  {
    imprimir( 'Ups..', 'Error al conectar con el servidor', 'error' );
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
              resetFormNew( );

              $( '.inv-news-active-new' ).addClass( 'd-none' );
              $( '.inv-news-home' ).removeClass( 'd-none' );
              $( '.inv-buttons' ).removeClass( 'd-none' );
              $( '.inv-back' ).addClass( 'd-none' );
              InvActualView = '.inv-news-home';

              window.scroll(0, 0);

              $( '#inv-instructions' ).html( 'Selecciona un activo y confirma su alta' );
            });
          });
        }
        else if ( result.dismiss === Swal.DismissReason.cancel )
        {
          getNewItems( );
          resetFormNew( );

          $( '.inv-news-active-new' ).addClass( 'd-none' );
          $( '.inv-news-home' ).removeClass( 'd-none' );
          $( '.inv-buttons' ).removeClass( 'd-none' );
          $( '.inv-back' ).addClass( 'd-none' );
          InvActualView = '.inv-news-home';

          window.scroll(0, 0);

          $( '#inv-instructions' ).html( 'Selecciona un activo y confirma su alta' );
        }
      });
    }
  });
}

function resetFormNew( )
{

  $( '#clp' ).val( '' );
  $( '#fechadecompra' ).val( '' );
  $( '#fechagarantia' ).val( '' );
  $( '#metodo_depreciacion' ).val( '' );
  $( '#fechastart' ).val( '' );
  $( '#vidautilnew' ).val( '' );
}

function IsConcilar( )
{

  let id = localStorage.getItem( 'new-inventary' );
  let base =
  `
    <thead>
      <tr>
        <th scope="col">Activo</th>
        <th scope="col">Asignación</th>
        <th scope="col">%</th>
      </tr>
    </thead>
    <tbody class="inventary-conciliacion-table">

    </tbody>
  `;
  $( '.inventary-conciliacion-table-content' ).html( base );

  $.ajax({
    url: url + `/inventario/concilar/${ id }`,
    type: 'GET',
    dataType: 'json'
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activos = response.activos;
      let aNuevos = response.nuevos;

      $( '.inventary-conciliacion-table' ).html( '' );
      activos.forEach( ( activo, i ) =>
      {
        let button;
        if( activo.porcentaje > 40 )
        {
          button =
          `
            <button type="button" class="btn btn-success btn-sm" name="button" onClick="conciliarDetails( ${ activo.id }, ${ activo.porcentaje } )">
              ${ activo.porcentaje }%
            </button>
          `;
        }
        else
        {
          button =
          `
            <button type="button" class="btn btn-primary btn-sm" name="button" onClick="conciliarDetails( ${ activo.id }, ${ activo.porcentaje } )">
              ${ activo.porcentaje }%
            </button>
          `;
        }

        let typePlantilla =
        `
          <tr>
            <td>
              <a class="text-dark text-decoration-none" onClick="infoItemConcilar( ${ activo.id } )">
                ${ activo.tipo }
                <br>
                ${ activo.nombre }
              </a>
            </td>
            <td class="align-middle">
              ${ activo.usuario }
            </td>
            <td class="align-middle">
              ${ button }
            </td>
          </tr>
        `;

        $( '.inventary-conciliacion-table' ).append( typePlantilla );
      });

      //borramos la tabla si existe
      if ( conciliarTable != null )
        conciliarTable.destroy( );

      //creamos la tabla dinamica
      conciliarTable = $( '.inventary-conciliacion-table-content' ).DataTable(
      {
        bInfo: false,
        searching: true,
        bLengthChange: false,
        pageLength: 5,
        language: spanish,
        aaSorting: [[ 2, "desc" ]],
      });

      $( '.inv-news-confirm' ).addClass( 'd-none' );
      $( '.inv-news-conciliar' ).removeClass( 'd-none' );
      $( '.inv-step' ).removeClass( 'd-none' );
      InvActualView = '.inv-news-conciliar';

      $( '.select-circle' ).css('background', '#ffde59');
      $( '.select-label' ).css('color', '#ffde59');
      $( '.confirm-circle' ).css('background', '#6c757d');
      $( '.confirm-label' ).css('color', '#6c757d');

      window.scroll(0, 0);
      $( '#inv-instructions' ).html( 'Selecciona el activo a conciliar' );
    }
  });
}

function infoItemConcilar( id )
{

  $.ajax({
    url: url + `/inventario/getActivoInfo/${ id }`,
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activo = response.activo;

      $( '#ciTipoActivo' ).val( activo.ID_Tipo );
      $( '#ciName' ).val( activo.Nom_Activo );
      $( '#ciSerie' ).val( activo.NSerie_Activo );
      $( '#ciCCosto' ).val( activo.ID_CC );
      $( '#ciAsignacion' ).val( activo.User_Inventario );
      $( '#ciEmpresa' ).val( activo.ID_Company );
      $( '#ciSucursal' ).val( activo.ID_Sucursal );
      $( '#ciArea' ).val( activo.ID_Area );
      $( '#ciDesc' ).val( activo.Desc_Activo );

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
          $( '.ci-image-front' ).html( response );
        }
        else
        {
          $( '.ci-image-front' ).html( '<i class="fas fa-5x fa-image"></i>' );
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
          $( '.ci-image-left' ).html( response );
        }
        else
        {
          $( '.ci-image-left' ).html( '<i class="fas fa-5x fa-image"></i>' );
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
          $( '.ci-image-right' ).html( response );
        }
        else
        {
          $( '.ci-image-right' ).html( '<i class="fas fa-5x fa-image"></i>' );
        }
      });

      $( '#conciliarInfoModal' ).modal( 'show' );
    }

  });
}

function conciliarDetails( id, porcentaje )
{
  localStorage.setItem( 'conciliar-activo', id );

  let actual = localStorage.getItem( 'new-inventary' );
  $( '.conciliar-porcentaje' ).html( porcentaje + '%' );

  $.ajax({
    url: url + `/inventario/concilarActivo`,
    type: 'POST',
    dataType: 'json',
    data: { old: id, actual: actual },
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let newA = response.actual[ 0 ];
      let oldA = response.old[ 0 ];

      $( '.conciliar-tipo-new' ).html( newA.Desc );
      $( '.conciliar-tipo-old' ).html( oldA.Desc );

      $( '.conciliar-serie-new' ).html( newA.NSerie_Activo );
      $( '.conciliar-serie-old' ).html( oldA.NSerie_Activo );

      $( '.conciliar-ubicacion-new' ).html( newA.empresa );
      $( '.conciliar-ubicacion-old' ).html( oldA.empresa );

      $( '.conciliar-cc-new' ).html( 'N/A' );
      $( '.conciliar-cc-old' ).html( 'N/A' );

      $( '.conciliar-asignacion-new' ).html( newA.nombre + newA.apellidos );
      $( '.conciliar-asignacion-old' ).html( oldA.nombre + oldA.apellidos );

      $( '#conciliarModal' ).modal( 'show' );
    }
  });

}

function ConfirmConciliar( )
{
  let id = localStorage.getItem( 'conciliar-activo' );

  let actual = localStorage.getItem( 'new-inventary' );

  $.ajax({
    url: url + `/inventario/concilarActivoConfirm`,
    type: 'POST',
    dataType: 'json',
    data: { old: id, actual: actual },
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let newA = response.actual[ 0 ];
      let oldA = response.old[ 0 ];

      let newFecha = newA.TS_Create.split( ' ' );
      let oldFecha = oldA.TS_Create.split( ' ' );

      let newPlantilla =
      `
        <tr>
          <td>
            ${ newA.Desc }
            <br>
            ${ newA.Nom_Activo }
          </td>
          <td class="align-middle">
            ${ newA.nombre + ' ' +newA.apellidos }
          </td>
          <td class="align-middle">
            ${ newFecha[ 0 ] }
          </td>
        </tr>
      `;

      $( '.conciliar-new' ).append( newPlantilla );

      let oldPlantilla =
      `
        <tr>
          <td>
            ${ oldA.Desc }
            <br>
            ${ oldA.Nom_Activo }
          </td>
          <td class="align-middle">
            ${ oldA.nombre + ' ' + oldA.apellidos }
          </td>
          <td class="align-middle">
            ${ oldFecha[ 0 ] }
          </td>
        </tr>
      `;

      $( '.conciliar-old' ).append( oldPlantilla );
      InvActualView = '.inv-news-conciliar-confirm';

      $( '.inv-news-conciliar' ).addClass( 'd-none' );
      $( '.inv-news-conciliar-confirm' ).removeClass( 'd-none' );

      $( '.select-circle' ).css('background', '#6c757d');
      $( '.select-label' ).css('color', '#6c757d');
      $( '.confirm-circle' ).css('background', '#ffde59');
      $( '.confirm-label' ).css('color', '#ffde59');

      window.scroll(0, 0);
      $( '#inv-instructions' ).html( 'Confirma la conciliación' );
    }
  });

}

function ConfirmConciliarMsg( )
{
  let id = localStorage.getItem( 'conciliar-activo' );

  let actual = localStorage.getItem( 'new-inventary' );

  $.ajax({
    url: url + `/inventario/conciliarFinish`,
    type: 'POST',
    dataType: 'json',
    data: { old: id, actual: actual },
  })
  .done( response =>
  {
    if ( response.status == 200 )
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
          getNewItems( );

          $( '.inv-news-conciliar-confirm' ).addClass( 'd-none' );
          $( '.inv-step' ).addClass( 'd-none' );
          $( '.inv-news-home' ).removeClass( 'd-none' );
          $( '.inv-buttons' ).removeClass( 'd-none' );
          $( '.inv-back' ).addClass( 'd-none' );
          InvActualView = '.inv-news-hone';

          window.scroll(0, 0);
          $( '#inv-instructions' ).html( 'Selecciona un activo y confirma su alta' );
        }
      });
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

      let depreciaciones = response.depreciacion;

      depreciaciones.forEach( ( depreciacion , i ) =>
      {

        let unidad = depreciacion.Observaciones.split( ' ' );

        let typePlantilla =
        `
          <option value="${ depreciacion.id }">
            ${ depreciacion.Metodo }
          </option>
        `;

        $( '#metodo_depreciacion' ).append( typePlantilla );

      });

    }
    else
    {
      imprimir( 'Ups..', 'Error al obtener la información del servidor', 'error' );
    }
  });
}

function setDepre( )
{
  let id = $( '#metodo_depreciacion' ).val( );
  switch ( id )
  {
    case '1':
      $( '#unidad-text' ).html( 'meses' );
      break;
    case '2':
      $( '#unidad-text' ).html( 'unidades' );
      break;
    case '3':
      $( '#unidad-text' ).html( 'kilometros' );
      break;
    case '4':
      $( '#unidad-text' ).html( 'horas' );
      break;
  }
}

function getNewItems( )
{

  let base =
  `
    <thead>
      <tr>
        <th scope="col">Activo</th>
        <th scope="col">Asignación</th>
        <th scope="col">Cargado</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody class="table-new-actives">

    </tbody>
  `;

  $( '.table-new-items' ).html( base );

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

      $( '.table-new-actives' ).html( '' );
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

      //borramos la tabla si existe
      if ( newTable != null )
        newTable.destroy();

      //creamos la tabla dinamica
      newTable = $( '.table-new-items' ).DataTable(
      {
        bInfo: false,
        searching: true,
        bLengthChange: false,
        pageLength: 5,
        language: spanish,
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
      $( '.new-image-front' ).html( '<i class="fas fa-spinner fa-spin"></i>' );
      $( '.new-image-left' ).html( '<i class="fas fa-spinner fa-spin"></i>' );
      $( '.new-image-left' ).html( '<i class="fas fa-spinner fa-spin"></i>' );

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

function getProcessItems( )
{
  let base1 =
  `
    <thead>
      <tr>
        <th scope="col">Activo</th>
        <th scope="col">Asignación</th>
        <th scope="col">Cargado</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody class="inventary-process-table">

    </tbody>
  `;

  let base2 =
  `
    <thead>
      <tr>
        <th scope="col">Activo</th>
        <th scope="col">Asignación</th>
        <th scope="col">Cargado</th>
      </tr>
    </thead>
    <tbody class="inventary-process-table2">

    </tbody>
  `;

  $( '.inventary-process-table-content' ).html( base1 );
  $( '.inventary-process-table2-content' ).html( base2 );

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

      $( '.inventary-process-table' ).html( '' );
      activos.forEach( ( activo, i ) =>
      {

        let typePlantilla =
        `
          <tr>
            <td>
              <a class="text-dark text-decoration-none" onClick="viewProcessInfo( ${ activo.id } )">
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

      //borramos la tabla si existe
      if ( procesoCTable != null )
        procesoCTable.destroy( );

      //creamos la tabla dinamica
      procesoCTable = $( '.inventary-process-table-content' ).DataTable(
      {
        bInfo: false,
        searching: true,
        bLengthChange: false,
        pageLength: 5,
        language: spanish,
      });

      $( '.inventary-process-table2' ).html( '' );
      aNuevos.forEach( ( activo, i ) =>
      {

        let typePlantilla =
        `
          <tr>
            <td>
              <a class="text-dark text-decoration-none" onClick="viewProcessInfo( ${ activo.id }, 0 )">
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

      //borramos la tabla si existe
      if ( procesoWTable != null )
        procesoWTable.destroy( );

      //creamos la tabla dinamica
      procesoWTable = $( '.inventary-process-table2-content' ).DataTable(
      {
        bInfo: false,
        searching: true,
        bLengthChange: false,
        pageLength: 5,
        language: spanish,
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

function viewProcessInfo( id, details = 1 )
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

      $( '#iTipoActivo' ).val( activo.ID_Tipo );
      $( '#iName' ).val( activo.Nom_Activo );
      $( '#iSerie' ).val( activo.NSerie_Activo );
      $( '#icCosto' ).val( activo.ID_CC );
      $( '#iAsignacion' ).val( activo.User_Inventario );
      $( '#iEmpresa' ).val( activo.ID_Company );
      $( '#iSucursal' ).val( activo.ID_Sucursal );
      $( '#iArea' ).val( activo.ID_Area );
      $( '#iDesc' ).val( activo.Desc_Activo );

      if ( details == 1 )
        $( '.modalProcessButton' ).removeClass( 'd-none' );
      else
        $( '.modalProcessButton' ).addClass( 'd-none' );

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
          $( '.process-image-front' ).html( response );
        }
        else
        {
          $( '.process-image-front' ).html( '<i class="fas fa-5x fa-image"></i>' );
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
          $( '.process-image-left' ).html( response );
        }
        else
        {
          $( '.process-image-left' ).html( '<i class="fas fa-5x fa-image"></i>' );
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
          $( '.process-image-right' ).html( response );
        }
        else
        {
          $( '.process-image-right' ).html( '<i class="fas fa-5x fa-image"></i>' );
        }
      });

      $( '#updateModal' ).modal( 'show' );
    }

  });
}

function getInventaryItems( )
{
  let base =
  `
    <thead>
      <tr>
        <th scope="col">Activo</th>
        <th scope="col">Asignación</th>
        <th scope="col">Cargado</th>
      </tr>
    </thead>
    <tbody class="table-inventary-actives">

    </tbody>
  `;

  $( '.table-inventary-actives-content' ).html( base );

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

      $( '.table-inventary-actives' ).html( '' );
      activos.forEach( ( activo, i ) =>
      {

        let typePlantilla =
        `
          <tr>
            <td>
              <a class="text-dark text-decoration-none" onClick="viewInvInfo( ${ activo.id } )">
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

      if ( inventarioTable != null )
        inventarioTable.destroy( );

      //creamos la tabla dinamica
      inventarioTable = $( '.table-inventary-actives-content' ).DataTable(
      {
        bInfo: false,
        searching: true,
        bLengthChange: false,
        pageLength: 5,
        language: spanish,
      });

      window.scroll(0, 0);
      $( '.inventary-count' ).html( response.number );
    }
    else
    {
      imprimir( 'Ups..', 'Error al obtener la información del servidor', 'error' );
    }
  });
}

function viewInvInfo( id )
{
  $( '.info-image-front' ).html( '<i class="fas fa-spinner fa-spin"></i>' );
  $( '.info-image-left' ).html( '<i class="fas fa-spinner fa-spin"></i>' );
  $( '.info-image-right' ).html( '<i class="fas fa-spinner fa-spin"></i>' );

  $.ajax({
    url: url + `/inventario/getActivoInfo/${ id }`,
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activo = response.activo;

      localStorage.setItem( 'process-inventary', activo.ID_Activo );

      $( '#infoTipoActivo' ).val( activo.ID_Tipo );
      $( '#infoName' ).val( activo.Nom_Activo );
      $( '#infoSerie' ).val( activo.NSerie_Activo );
      $( '#infocCosto' ).val( activo.ID_CC );
      $( '#infoAsignacion' ).val( activo.User_Inventario );
      $( '#infoEmpresa' ).val( activo.ID_Company );
      $( '#infoSucursal' ).val( activo.ID_Sucursal );
      $( '#infoArea' ).val( activo.ID_Area );
      $( '#infoDesc' ).val( activo.Desc_Activo );

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
          $( '.info-image-front' ).html( response );
        }
        else
        {
          $( '.info-image-front' ).html( '<i class="fas fa-5x fa-image"></i>' );
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
          $( '.info-image-left' ).html( response );
        }
        else
        {
          $( '.info-image-left' ).html( '<i class="fas fa-5x fa-image"></i>' );
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
          $( '.info-image-right' ).html( response );
        }
        else
        {
          $( '.info-image-right' ).html( '<i class="fas fa-5x fa-image"></i>' );
        }
      });

      $( '#infoModal' ).modal( 'show' );
    }

  });
}

function inventaryFiltros( )
{

  let base =
  `
    <thead>
      <tr>
        <th scope="col">Activo</th>
        <th scope="col">Asignación</th>
        <th scope="col">Cargado</th>
      </tr>
    </thead>
    <tbody class="table-inventary-actives">

    </tbody>
  `;

  $( '.table-inventary-actives-content' ).html( base );


  let filtros =
  {
    tipo: $( '#invFTipo' ).val( ),
    cc: $( '#invFCC' ).val( ),
    empresa: $( '#invFEmpresa' ).val( ),
    sucursal: $( '#invFSucursal' ).val( ),
    area: $( '#invFArea' ).val( ),
  };

  $.ajax({
    url: url + '/inventario/getInventaryItemsFilter',
    type: 'POST',
    dataType: 'json',
    data: filtros,
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activos = response.activos;
      let aNuevos = response.nuevos;

      $( '.table-inventary-actives' ).html( '' );
      activos.forEach( ( activo, i ) =>
      {

        let typePlantilla =
        `
          <tr>
            <td>
              <a class="text-dark text-decoration-none" onClick="viewInvInfo( ${ activo.id } )">
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

      if ( inventarioTable != null )
        inventarioTable.destroy( );

      //creamos la tabla dinamica
      inventarioTable = $( '.table-inventary-actives-content' ).DataTable(
      {
        bInfo: false,
        searching: true,
        bLengthChange: false,
        pageLength: 5,
        language: spanish,
      });

      $( '.inventary-count' ).html( response.number );
    }
    else
    {
      imprimir( 'Ups..', 'Error al obtener la información del servidor', 'error' );
    }
  });
}

function navStepsInv( )
{

}

/* --- Bajas --- */

function down( )
{

  let base =
  `
    <thead>
      <tr>
        <th></th>
        <th scope="col">Activo</th>
        <th scope="col">Asignación</th>
        <th scope="col">Cargado</th>
        <th></th>
      </tr>
    </thead>
    <tbody class="table-down-actives">

    </tbody>
  `;

  $( '.table-down-actives-content' ).html( base );

  $.ajax({
    url: url + '/bajas/getItems',
    type: 'GET',
    dataType: 'json'
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activos = response.activos;

      $( '.table-down-actives' ).html( '' );
      activos.forEach( ( activo, i ) =>
      {

        let typePlantilla =
        `
          <tr>
            <td>
              <input type="checkbox" name="select_${ activo.id }" onClick="downCheckbox( this )" class="downCheck">
            </td>
            <td>
              <a class="text-dark text-decoration-none" onClick="viewDownInfo( ${ activo.id } )">
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
            <td>
              <button type="button" class="btn btn-danger btn-sm" name="button" onclick="deleteDown( ${ activo.id } )">
                <i class="fas fa-angle-right"></i>
              </button>
            </td>
          </tr>
        `;

        $( '.table-down-actives' ).append( typePlantilla );

      });

      if ( downTable != null )
        downTable.destroy( );

      //creamos la tabla dinamica
      downTable = $( '.table-down-actives-content' ).DataTable(
      {
        bInfo: false,
        searching: true,
        bLengthChange: false,
        pageLength: 5,
        language: spanish,
      });

      $( '.down-count' ).html( response.number );
    }
    else
    {
      imprimir( 'Ups..', 'Error al obtener la información del servidor', 'error' );
    }
  });
}

function downFiltros( )
{

  let base =
  `
    <thead>
      <tr>
        <th></th>
        <th scope="col">Activo</th>
        <th scope="col">Asignación</th>
        <th scope="col">Cargado</th>
        <th></th>
      </tr>
    </thead>
    <tbody class="table-down-actives">

    </tbody>
  `;

  $( '.table-down-actives-content' ).html( base );


  let filtros =
  {
    tipo: $( '#downTipo' ).val( ),
    cc: $( '#downCC' ).val( ),
    empresa: $( '#downEmpresa' ).val( ),
    sucursal: $( '#downSucursal' ).val( ),
    area: $( '#downArea' ).val( ),
  };

  $.ajax({
    url: url + '/bajas/getItemsFilter',
    type: 'POST',
    dataType: 'json',
    data: filtros,
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activos = response.activos;

      $( '.table-down-actives' ).html( '' );
      activos.forEach( ( activo, i ) =>
      {

        let typePlantilla =
        `
          <tr>
            <td>
              <input type="checkbox" name="select_${ activo.id }" onClick="downCheckbox( this )" class="downCheck">
            </td>
            <td>
              <a class="text-dark text-decoration-none" onClick="viewDownInfo( ${ activo.id } )">
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
            <td>
              <button type="button" class="btn btn-danger btn-sm" name="button" onclick="deleteDown( ${ activo.id } )">
                <i class="fas fa-angle-right"></i>
              </button>
            </td>
          </tr>
        `;

        $( '.table-down-actives' ).append( typePlantilla );

      });

      if ( downTable != null )
        downTable.destroy( );

      //creamos la tabla dinamica
      downTable = $( '.table-down-actives-content' ).DataTable(
      {
        bInfo: false,
        searching: true,
        bLengthChange: false,
        pageLength: 5,
        language: spanish,
      });

      $( '.down-count' ).html( response.number );
    }
    else
    {
      imprimir( 'Ups..', 'Error al obtener la información del servidor', 'error' );
    }
  });
}

function downCheckbox( node )
{
  let num = 0;
  $( '.downCheck:checked' ).each(function()
  {
    num++;
  });

  if ( num > 0)
    $( '.delete-button-down' ).removeClass( 'd-none' );
  else
    $( '.delete-button-down' ).addClass( 'd-none' );
}

function deleteDown( id )
{
  localStorage.setItem( 'down-item', id );

  $( '#deleteActivo' ).modal( 'show' );
}

function multipleDelete( )
{
  let data = [ ];
  $( '.downCheck:checked' ).each(function( )
  {
    let getId = this.name.split( '_' )[ 1 ];
    data.push( getId );
  });

  localStorage.setItem( 'down-item', data );

  $( '#deleteActivo' ).modal( 'show' );
}

function confirmDownDelete( )
{
  let deleteItems = localStorage.getItem( 'down-item' );

  let json =
  {
    items: deleteItems,
    motivo: $( '#down-select' ).val( ),
    desc: $( '#motivo-down' ).val( ),
  };

  $.ajax({
    url: url + '/bajas/down',
    type: 'POST',
    dataType: 'json',
    data: json,
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      $( '#deleteActivo' ).modal( 'hide' );
      $( '#motivo-down' ).val( '' );
      $( '#down-select' ).val( '1' );

      Swal.fire(
      {
        title: '¡Listo!',
        text: 'El/Los activo(s) han sido dados de baja exitosamente',
        icon: 'success',
        confirmButtonColor: '#5cb85c',
      })
      .then( result =>
      {
        downFiltros( );
      });
    }
  });
}

function viewDownInfo( id )
{
  $.ajax({
    url: url + `/inventario/getActivoInfo/${ id }`,
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let activo = response.activo;

      localStorage.setItem( 'process-inventary', activo.ID_Activo );

      $( '#downTipoActivo' ).val( activo.ID_Tipo );
      $( '#downName' ).val( activo.Nom_Activo );
      $( '#downSerie' ).val( activo.NSerie_Activo );
      $( '#downcCosto' ).val( activo.ID_CC );
      $( '#downAsignacion' ).val( activo.User_Inventario );
      $( '#downEmpresa' ).val( activo.ID_Company );
      $( '#downSucursal' ).val( activo.ID_Sucursal );
      $( '#downArea' ).val( activo.ID_Area );
      $( '#downDesc' ).val( activo.Desc_Activo );

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
          $( '.down-image-front' ).html( response );
        }
        else
        {
          $( '.down-image-front' ).html( '<i class="fas fa-5x fa-image"></i>' );
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
          $( '.down-image-left' ).html( response );
        }
        else
        {
          $( '.down-image-left' ).html( '<i class="fas fa-5x fa-image"></i>' );
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
          $( '.down-image-right' ).html( response );
        }
        else
        {
          $( '.down-image-right' ).html( '<i class="fas fa-5x fa-image"></i>' );
        }
      });

      $( '#downInfoModal' ).modal( 'show' );
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
        case '.down':
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
        case '.down':
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
        case '.down':
          $( actualView ).addClass( 'd-none' );
          break;
      }

      actualView = '.scanner';
      $( actualView ).removeClass( 'd-none' );
    }

  });

  $( '#down' ).click( event =>
  {

    event.preventDefault( );

    //activamos el color
    activeItem( '#down' );

    if ( actualView != '.down' )
    {
      switch ( actualView )
      {
        case '.home':
          $( actualView ).addClass( 'd-none' );
          break;
        case '.inventary':
          $( actualView ).addClass( 'd-none' );
          break;
        case '.scanner':
          $( actualView ).addClass( 'd-none' );
          break;
      }

      actualView = '.down';

      down( );

      $( actualView ).removeClass( 'd-none' );
    }


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

  $( '#numActivoS2' ).keydown( e =>
  {
    if ( e.keyCode == 32 || e.keyCode == 192 )
      return false;
    else
      return true;
  });

  //ready
  $( '#without-scan' ).click( event =>
  {

    wizzardPreviewView = wizzardActualView;
    wizzardActualView = '.scanner-without-scan';

    setInsMessage( wizzardActualView );

    $( wizzardPreviewView ).addClass( 'd-none' );
    $( wizzardActualView ).removeClass( 'd-none' );

    window.scroll(0, 0);
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
        actualStepScanner = 2;

        if ( response.activo.ID_MetDepre != null )
        {
          switch ( response.activo.ID_MetDepre )
          {
            case '0':
              $( '#scanner-vida-util' ).html( '( hr / km / un )' );
              break;
            case '1':
              $( '#scanner-vida-util' ).html( '( meses )' );
              break;
            case '2':
              $( '#scanner-vida-util' ).html( '( unidades )' );
              break;
            case '3':
              $( '#scanner-vida-util' ).html( '( kilometros )' );
              break;
            case '4':
              $( '#scanner-vida-util' ).html( '( horas )' );
              break;
          }
        }

        wizzardPreviewView = wizzardActualView;
        wizzardActualView = '.scanner-status';

        setInsMessage( wizzardActualView, true );

        $( wizzardPreviewView ).addClass( 'd-none' );
        $( wizzardActualView ).removeClass( 'd-none' );

        window.scroll(0, 0);
      }
      else
      {
        Swal.fire({
          icon: 'error',
          title: 'Ups..',
          text: response.msg,
          allowOutsideClick: false,
          showCancelButton: true,
          confirmButtonColor: '#5cb85c',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Crear como nuevo',
          cancelButtonText: 'Aceptar',
        })
        .then((result) => {
          if (result.isConfirmed)
          {
            localStorage.setItem( 'codigo', $( '#numActivoS1' ).val( ) );
            isNew = true;

            wizzardPreviewView = wizzardActualView;
            wizzardActualView = '.scanner-form';

            setInsMessage( wizzardActualView );

            $( wizzardPreviewView ).addClass( 'd-none' );
            $( wizzardActualView ).removeClass( 'd-none' );

            window.scroll(0, 0);
          }
        });
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

    window.scroll(0, 0);
  });

  //ready
  $( '#new-scan' ).click( event =>
  {

    wizzardPreviewView = wizzardActualView;
    wizzardActualView = '.scanner-new';

    setInsMessage( wizzardActualView );

    $( wizzardPreviewView ).addClass( 'd-none' );
    $( wizzardActualView ).removeClass( 'd-none' );

    window.scroll(0, 0);
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

        window.scroll(0, 0);
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
        actualStepScanner = 2;

        wizzardPreviewView = wizzardActualView;
        wizzardActualView = '.scanner-form';

        setInsMessage( wizzardActualView );

        $( wizzardPreviewView ).addClass( 'd-none' );
        $( wizzardActualView ).removeClass( 'd-none' );

        window.scroll(0, 0);
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

        window.scroll(0, 0);
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
          actualStepScanner = 3;

          setInsMessage( wizzardActualView );

          $( wizzardPreviewView ).addClass( 'd-none' );
          $( wizzardActualView ).removeClass( 'd-none' );

          window.scroll(0, 0);
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

    $( '#scanner-image-front' ).html( '<span>Sin imagen</span>' );
    $( '#scanner-image-right' ).html( '<span>Sin imagen</span>' );
    $( '#scanner-image-left' ).html( '<span>Sin imagen</span>' );

    wizzardPreviewView = wizzardActualView;
    wizzardActualView = '.scanner-start';
    actualStepScanner = 1;

    setInsMessage( wizzardActualView );

    $( wizzardPreviewView ).addClass( 'd-none' );
    $( wizzardActualView ).removeClass( 'd-none' );

    window.scroll(0, 0);
  });

  /* --- inventario --- */

  $( '#fechadecompra' ).attr( 'max', new Date().toISOString().split("T")[0] );

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

    window.scroll(0, 0);
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

    window.scroll(0, 0);
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

    window.scroll(0, 0);
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
          $( '.inv-back' ).addClass( 'd-none' );
        });
      }
    });
  });

  $( '.inv-back' ).click( event =>
  {
    event.preventDefault( );
    console.log( InvActualView );
    switch ( InvActualView )
    {
      case '.inv-news-confirm':
        InvActualView = '.inv-news-home';
        $( '.inv-news-confirm' ).addClass( 'd-none' );
        $( InvActualView ).removeClass( 'd-none' );
        $( '#inv-instructions' ).html( 'Selecciona un activo y confirma su alta' );
        $( '.inv-back' ).addClass( 'd-none' );

        window.scroll(0, 0);
        break;
      case '.inv-news-active-new':
        InvActualView = '.inv-news-confirm';
        $( '.inv-news-active-new' ).addClass( 'd-none' );
        $( '#inv-instructions' ).html( 'Confirmar alta de activo' );
        $( InvActualView ).removeClass( 'd-none' );

        window.scroll(0, 0);
        break;
      case '.inv-news-conciliar':
        InvActualView = '.inv-news-confirm';
        $( '.inv-news-conciliar' ).addClass( 'd-none' );
        $( InvActualView ).removeClass( 'd-none' );
        $( '#inv-instructions' ).html( 'Confirmar alta de activo' );
        $( '.inv-step' ).addClass( 'd-none' );

        window.scroll(0, 0);
        break;
      case '.inv-news-conciliar-confirm':
        InvActualView = '.inv-news-conciliar';
        $( '.inv-news-conciliar-confirm' ).addClass( 'd-none' );
        $( InvActualView ).removeClass( 'd-none' );
        $( '#inv-instructions' ).html( 'Selecciona el activo a conciliar' );

        $( '.select-circle' ).css('background', '#ffde59');
        $( '.select-label' ).css('color', '#ffde59');
        $( '.confirm-circle' ).css('background', '#6c757d');
        $( '.confirm-label' ).css('color', '#6c757d');

        window.scroll(0, 0);
        break;
      default:

    }
  });

  /* --- Bajas --- */

  $( '#down-select' ).change( (event) =>
  {
    $( '.motivo-down-form' ).removeClass( 'd-none' );
  });

});
