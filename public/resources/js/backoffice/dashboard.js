'use strict'

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


$(document).ready(function( )
{
  //URL del servidor
  let url = $('#url').val( );

  //Vista actual
  let actualView = '.home';

  //funciones para la navegación de la barra
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

  /* --- wizzard --- */


});
