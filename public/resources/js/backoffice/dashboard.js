
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
      message = 'Selecciona el tipo de etiqueta que tiene el activo';
      break;
    case '.scanner-status':
      message = 'Estás inventariando';
      break;
    case '.scanner-form':
      if ( update )
        message = 'Edita los datos del activo';
      else
        message = 'Ingresa los datos del activo';
      break;
    case '.scanner-geolocation':
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

  let formData = new FormData( );

  formData.set( 'type', type );
  formData.set( 'activo', localStorage.getItem( 'codigo' ) );
  formData.append( 'file', imagen );

  console.log( imagen );

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
          <img class="img-fluid" src="${ img }" style="width: 250px;" >
        </a>
      `;

      $( `#scanner-image-${ type }` ).html( plantilla );
    }
    else
    {
      imprimir( 'Ups...', response.msg, 'error' );
    }
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
         $( '#serie' ).val( ) == '' ||
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

  $( '#inv-new' ).click( event =>
  {
    event.preventDefault( );

    //colocamos
    setBackgroundButtons( 'new' );

    //mostramos
    $( '.inv-inv-table' ).addClass( 'd-none' );
    $( '.inv-update-table' ).addClass( 'd-none' );

    $( '.inv-news-table' ).removeClass( 'd-none' );
  });

  $( '#inv-update' ).click( event =>
  {
    event.preventDefault( );

    setBackgroundButtons( 'update' );

    //mostramos
    $( '.inv-news-table' ).addClass( 'd-none' );
    $( '.inv-inv-table' ).addClass( 'd-none' );

    $( '.inv-update-table' ).removeClass( 'd-none' );
  });

  $( '#inv-inv' ).click( event =>
  {
    event.preventDefault( );

    setBackgroundButtons( 'inv' );

    //mostramos
    $( '.inv-news-table' ).addClass( 'd-none' );
    $( '.inv-update-table' ).addClass( 'd-none' );

    $( '.inv-inv-table' ).removeClass( 'd-none' );
  });

});
