
//variables globales
var url = $('#url').val( );
var lon;
var lat;
var isNew = false;

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

  var activeMap = L.map( 'activeMap' ).setView( [ lat, lon ], 16 );

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
        //pasamos a la siguiente vista
        console.log( res );
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
    scanBarCodeZebra();
  }

}

function scanBarCodeZebra()
{
  const codeReader = new ZXing.BrowserBarcodeReader();
  const img = $( '#barcode-img' )[0].cloneNode(true);

  codeReader.decodeFromImage(img)
            .then(result =>
            {
              //siguiente paso
            })
            .catch(err =>
            {
              imprimir( 'Error', 'No se detectó el código de barras. Intente de nuevo', 'error' );
            });
}

function scanBarCodeQuagga( image )
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
      //siguiente paso
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
        message = 'Nueva ubicación geográfica del activo';
        $( '#instructions2' ).html( 'Indica el área donde se encuentra el activo' );
      }
      else
      {
        message = 'Ubicación geográfica del activo';
        $( '#instructions2' ).html( 'Indica el área donde se encontrará el activo' );
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

$(document).ready(function( )
{
  //URL del servidor
  let url = $('#url').val( );

  //localización
  navigator.geolocation.getCurrentPosition( setCoordenadasMapG );

  //Vista actual
  let actualView = '.home';

  //funciones para la navegación de la barra
  $( '#inventario' ).click( event =>
  {

    event.preventDefault( );

    //activamos el color
    activeItem( '#inventario' );

    if ( actualView != '.home' )
    {
      switch ( actualView )
      {
        case '.scanner':
          $( actualView ).addClass( 'd-none' );
          break;
      }

      actualView = '.home';
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

  /* --- scanner --- */
  let wizzardActualView = '.scanner-start';
  let wizzardPreviewView = '.scanner-start';

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

        $( '#scanner-subtipo' ).html( response.activo.ID_Tipo );
        $( '#scanner-nombre' ).html( response.activo.Nom_Activo );
        $( '#scanner-serie' ).html( response.activo.NSerie_Activo );
        $( '#scanner-asignacion' ).html( 'N/A' );
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

    localStorage.setItem( 'codigo', codigo );
    isNew = true;

    wizzardPreviewView = wizzardActualView;
    wizzardActualView = '.scanner-form';

    setInsMessage( wizzardActualView );

    $( wizzardPreviewView ).addClass( 'd-none' );
    $( wizzardActualView ).removeClass( 'd-none' );
  });

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

  $( '#nextGeo' ).click( event =>
  {
    event.preventDefault( );

    wizzardPreviewView = wizzardActualView;
    wizzardActualView = '.scanner-photos';

    setInsMessage( wizzardActualView );

    $( wizzardPreviewView ).addClass( 'd-none' );
    $( wizzardActualView ).removeClass( 'd-none' );
  });

  $( '#scanFinish' ).click( event =>
  {

    imprimir( '¡Hecho!', 'Activo cargado exitosamente', 'success' );

    event.preventDefault( );

    wizzardPreviewView = wizzardActualView;
    wizzardActualView = '.scanner-start';

    setInsMessage( wizzardActualView );

    $( wizzardPreviewView ).addClass( 'd-none' );
    $( wizzardActualView ).removeClass( 'd-none' );

  });

});
