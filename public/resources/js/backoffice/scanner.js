//variables globales
var url = $('#url').val( );
var isNew = false;
var activeMap;
var actualStepScanner = 1;
var actualStepInv = 1;
var lon = 0;
var lat = 0;
var imageChange = false;
var gpsChange = false;

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
  $( '#tipoActivo' ).html( '' );
  $( '#asignacion' ).html( '' );
  $( '#empresas' ).html( '' );
  $( '#cCosto' ).html( '' );
  $( '#area' ).html( '' );

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
        let option = new Option(usuario.nombre + ' ' + usuario.apellidos, usuario.id_usuario, false, false);
        $( '#asignacion' ).append( option ).trigger('change');
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

      let cc = response.cc;

      cc.forEach( ( ccUnico , i ) =>
      {

        let typePlantilla =
        `
          <option value="${ ccUnico.id }">${ ccUnico.Desc }</option>
        `;

        $( '#cCosto' ).append( typePlantilla );

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

      let areas = response.areas;
    
      areas.forEach( ( area , i ) =>
      {

        let typePlantilla =
        `
          <option value="${ area.id }">${ area.descripcion }</option>
        `;

        $( '#area' ).append( typePlantilla );

      });

    }
    else
    {
      imprimir( 'Ups..', response.msg, 'error' );
    }
  });
}

function setCoordenadasActiveMap( position )
{
  if (lon == 0 && lat == 0) 
  {
    lon = position.coords.longitude;
    lat = position.coords.latitude; 
  }
  activeMap = L.map( 'activeMap' ).setView( [ lat, lon ], 16 );

  activeMap.addControl(new L.Control.Fullscreen());

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

function setCoordenadasActiveMapAjax( position )
{
  
  lon = position.coords.longitude;
  lat = position.coords.latitude; 
  
  activeMap = L.map( 'activeMap' ).setView( [ lat, lon ], 16 );

  activeMap.addControl(new L.Control.Fullscreen());

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

   let gps = `${ lat },${ lon }`;

   $.ajax({
     url: url + '/activos/coordenadas',
     type: 'POST',
     dataType: 'json',
     data: { gps: gps, codigo: localStorage.getItem( 'codigo' ), },
   })
   .done( response =>
   {
     if (response.status == 200)
     {
       imprimir('¡Hecho!', 'Ubicación geográfica del activo actualizada', 'success');
     }
     else
     {
       imprimir( 'Ups..', response.msg, 'error' );
     }
   });
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
        //message = 'Indica el avance en la vida útil del activo';
        message = 'Nueva ubicación geográfica del activo';
        //$( '#instructions2' ).html( 'Nueva ubicación geográfica del activo' );
        $( '#instructions3' ).html( 'Indica el área donde se encuentra el activo' );
      }
      else
      {
        //message = 'Indica el avance en la vida útil del activo';
        message = 'Ubicación geográfica del activo';
        //$( '#instructions2' ).html( 'Ubicación geográfica del activo' );
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

      imageChange = true;
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

function updateCoordenadas( )
{
  lon = 0;
  lat = 0;
  activeMap.off( );
  activeMap.remove( );
  gpsChange = true;

  navigator.geolocation.getCurrentPosition( setCoordenadasActiveMapAjax );
}

$(document).ready(function( )
{

  $('#asignacion').select2({ theme: 'bootstrap4', });

  //formularios
  getScannerFormData( );

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
    $('.scanner-back').removeClass('d-none');

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
      $( '#sucursal' ).html( '' );
      $( '#area' ).html( '' );
      if (response.status == 200)
      {
        let sucursales = response.sucursal;
      
        sucursales.forEach( ( sucursal , i ) =>
        {
  
          let typePlantilla =
          `
            <option value="${ sucursal.id }">${ sucursal.Desc }</option>
          `;
  
          $( '#sucursal' ).append( typePlantilla );
  
        });

        let areas = response.areas;
      
        areas.forEach( ( area , i ) =>
        {
  
          let typePlantilla =
          `
            <option value="${ area.id }">${ area.descripcion }</option>
          `;
  
          $( '#area' ).append( typePlantilla );
  
        });

        $( '#scanner-subtipo' ).html( response.tipo.Desc );
        $( '#scanner-nombre' ).html( response.activo.Nom_Activo );
        $( '#scanner-serie' ).html( response.activo.NSerie_Activo );
        if (response.user != null)
          $( '#scanner-asignacion' ).html( response.user.nombre + ' ' + response.user.apellidos ); 
        else 
          $( '#scanner-asignacion' ).html( 'Sin usuario' ); 
        $( '#vidaUtil' ).val( response.activo.Vida_Activo );
        $( '#empresas' ).val( response.activo.ID_Company );
        $( '#sucursal' ).val( response.activo.ID_Sucursal );
        $( '#area' ).val( response.activo.ID_Area );
        lon = response.activo.GPS.split(',')[1];
        lat = response.activo.GPS.split(',')[0];
        isNew = false;
        actualStepScanner = 2;
        localStorage.setItem( 'codigo', $( '#numActivoS1' ).val( ) );

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
            isNew = true;
            localStorage.setItem( 'codigo', $( '#numActivoS1' ).val( ) );
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
    $('.scanner-back').removeClass('d-none');

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
        $('#asignacion').select2({ theme: 'bootstrap4', }).select2('val', response.activo.User_Inventario)
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

    $('#submit-button-form').html('<i class="fas fa-circle-notch fa-spin"></i> Cargando');
    $('#submit-button-form').prop('disabled', true);

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

        $('#submit-button-form').html('Continuar');
        $('#submit-button-form').prop('disabled', false);
      }
      else
      {
        imprimir( 'Ups..', response.msg, 'error' );
      }
    });

  });

  $( '#empresas' ).change( event =>
  {
    let data = 
    {
      empresa: $( '#empresas' ).val( ),
    };

    //buscamos el codigo en la BDD
    $.ajax({
      url: url + '/activos/dinamicForm',
      type: 'POST',
      dataType: 'json',
      data: data
    })
    .done( response =>
    {
      if (response.status == 200)
      {
        $( '#sucursal' ).html( '' );
        $( '#area' ).html( '' );

        response.sucursales.forEach( ( sucursal , i ) =>
        {

          let typePlantilla =
          `
            <option value="${ sucursal.id }">${ sucursal.Desc }</option>
          `;

          $( '#sucursal' ).append( typePlantilla );

        });

        response.areas.forEach( ( area , i ) =>
        {

          let typePlantilla =
          `
            <option value="${ area.id }">${ area.descripcion }</option>
          `;

          $( '#area' ).append( typePlantilla );

        });
      }
      else
      {
        imprimir( 'Ups..', response.msg, 'error' );
      }
    });
  });

  //ready
  $( '#nextGeo' ).click( event =>
  {
    event.preventDefault( );

    //reunimos la informacion en un JSON
    let data =
    {
      codigo: localStorage.getItem( 'codigo' ),
      vida: $( '#vidaUtil' ).val( ),
      //empresa: $( '#empresas' ).val( ),
      sucursal: $( '#sucursal' ).val( ),
      area: $( '#area' ).val( ),
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

    let dateUpdate = false;

    if (imageChange && gpsChange)
      dateUpdate = true;

    //ajax de comprobación
    let data = 
    {
      activo: localStorage.getItem( 'codigo' ),
      inventariar: dateUpdate,
    };

    //buscamos el codigo en la BDD
    $.ajax({
      url: url + '/activos/updateActivo',
      type: 'POST',
      dataType: 'json',
      data: data
    })
    .done( response =>
    {
      
    });

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
    $( '#vidaUtil' ).val( '' );

    $( '#scanner-image-front' ).html( '<span>Sin imagen</span>' );
    $( '#scanner-image-right' ).html( '<span>Sin imagen</span>' );
    $( '#scanner-image-left' ).html( '<span>Sin imagen</span>' );
    $('.scanner-back').addClass('d-none');
    lon = 0;
    lat = 0;
    imageChange = false;
    gpsChange = false;

    wizzardPreviewView = wizzardActualView;
    wizzardActualView = '.scanner-start';
    actualStepScanner = 1;

    setInsMessage( wizzardActualView );

    $( wizzardPreviewView ).addClass( 'd-none' );
    $( wizzardActualView ).removeClass( 'd-none' );

    getScannerFormData( );

    window.scroll(0, 0);
  });

  $('.scanner-back').click( event =>
  {
    event.preventDefault();

    switch(wizzardActualView)
    {
      case '.scanner-without-scan':
        $( '.scan-circle' ).css('background', '#e6c84f');
        $( '.scan-label' ).css('color', '#e6c84f');
        $( '.update-circle' ).css('background', '#6c757d');
        $( '.update-label' ).css('color', '#6c757d');
        $( '.photo-circle' ).css('background', '#6c757d');
        $( '.photo-label' ).css('color', '#6c757d');
        message = 'Selecciona el tipo de etiqueta que tiene el activo';

        $( '.scanner-without-scan' ).addClass( 'd-none' );
        $( '.scanner-start' ).removeClass( 'd-none' );
        $('.scanner-back').addClass('d-none');
        wizzardActualView = '.scanner-start';
        break;
      case '.scanner-status':
        $( '.scan-circle' ).css('background', '#e6c84f');
        $( '.scan-label' ).css('color', '#e6c84f');
        $( '.update-circle' ).css('background', '#6c757d');
        $( '.update-label' ).css('color', '#6c757d');
        $( '.photo-circle' ).css('background', '#6c757d');
        $( '.photo-label' ).css('color', '#6c757d');
        message = 'Selecciona el tipo de etiqueta que tiene el activo';

        $( '.scanner-status' ).addClass( 'd-none' );
        $( '.scanner-start' ).removeClass( 'd-none' );
        $('.scanner-back').addClass('d-none');
        wizzardActualView = '.scanner-start';
        break;
      case '.scanner-form':
        $( '.scan-circle' ).css('background', '#6c757d');
        $( '.scan-label' ).css('color', '#6c757d');
        $( '.update-circle' ).css('background', '#e6c84f');
        $( '.update-label' ).css('color', '#e6c84f');
        $( '.photo-circle' ).css('background', '#6c757d');
        $( '.photo-label' ).css('color', '#6c757d');
        message = 'Estás inventariando';

        $( '.scanner-form' ).addClass( 'd-none' );
        $( '.scanner-status' ).removeClass( 'd-none' );
        wizzardActualView = '.scanner-status';
        break;
      case '.scanner-geolocation':
        $( '.scan-circle' ).css('background', '#6c757d');
        $( '.scan-label' ).css('color', '#6c757d');
        $( '.update-circle' ).css('background', '#e6c84f');
        $( '.update-label' ).css('color', '#e6c84f');
        $( '.photo-circle' ).css('background', '#6c757d');
        $( '.photo-label' ).css('color', '#6c757d');
        message = 'Estás inventariando';

        $( '.scanner-geolocation' ).addClass( 'd-none' );
        $( '.scanner-status' ).removeClass( 'd-none' );
        wizzardActualView = '.scanner-status';
        break;
      case '.scanner-photos':
        $( '.scan-circle' ).css('background', '#6c757d');
        $( '.scan-label' ).css('color', '#6c757d');
        $( '.update-circle' ).css('background', '#e6c84f');
        $( '.update-label' ).css('color', '#e6c84f');
        $( '.photo-circle' ).css('background', '#6c757d');
        $( '.photo-label' ).css('color', '#6c757d');
        if ( isNew )
        {
          //message = 'Indica el avance en la vida útil del activo';
          message = 'Ubicación geográfica del activo';
          //$( '#instructions2' ).html( 'Nueva ubicación geográfica del activo' );
          $( '#instructions3' ).html( 'Indica el área donde se encuentra el activo' );
        }
        else
        {
          //message = 'Indica el avance en la vida útil del activo';
          message = 'Ubicación geográfica del activo';
          //$( '#instructions2' ).html( 'Ubicación geográfica del activo' );
          $( '#instructions3' ).html( 'Indica el área donde se encontrará el activo' );
        }

        $( '.scanner-photos' ).addClass( 'd-none' );
        $( '.scanner-geolocation' ).removeClass( 'd-none' );
        wizzardActualView = '.scanner-geolocation';
        break;
      case '.scanner-new':
        $( '.scan-circle' ).css('background', '#e6c84f');
        $( '.scan-label' ).css('color', '#e6c84f');
        $( '.update-circle' ).css('background', '#6c757d');
        $( '.update-label' ).css('color', '#6c757d');
        $( '.photo-circle' ).css('background', '#6c757d');
        $( '.photo-label' ).css('color', '#6c757d');
        message = 'Selecciona el tipo de etiqueta que tiene el activo';

        $( '.scanner-new' ).addClass( 'd-none' );
        $( '.scanner-start' ).removeClass( 'd-none' );
        $('.scanner-back').addClass('d-none');
        wizzardActualView = '.scanner-start';
        break;
    }

  });

  $('#combo-empresas').change( event => 
  {
    let json = 
    {
      id: $('#combo-empresas').val(),
    };

    //subir a servidor
    $.ajax({
      url: url + '/empresas/changeCompany',
      type: 'POST',
      dataType: 'json',
      data: json,
    })
    .done( response =>
    {
      if ( response.status == 200 )
      {
        Swal.fire({
          icon: 'success',
          title: '¡Hecho!',
          text: response.msg,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) 
            location.reload();
        });
      }
      else
        imprimir( 'Ups...', response.msg, 'error' );
    })
    .fail( ( ) =>
    {
      imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
    });
  });

  $('#asignacion').change( event => 
  {
    let id = $('#asignacion').val();

    $.ajax({
      url: url + `/usuarios/getcc`,
      type: 'POST',
      dataType: 'json',
      data: { id: id },
    })
    .done( response =>
    {
      if (response.status == 200) 
      {
        $('#cCosto').val(response.data.id_cc);
      }
    });
  });

  $('#copy-url').click( event => {
    
    let url = window.location.href;

    var aux = document.createElement('input');
    aux.setAttribute('value', url);
    document.body.appendChild(aux);
    aux.select();
    if (document.execCommand('copy'))
    {
      document.body.removeChild(aux);
      imprimir( '', 'URL copiada al portapales', 'success' );
    }
    else
    {
      document.body.removeChild(aux);
      imprimir('Ups...', 'Ah ocurrido un problema al copiar la url', 'error' );
    }  
  });

});