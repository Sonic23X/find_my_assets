//variables globales
var url = $('#url').val( );
var lon;
var lat;

function imprimir ( titulo, mensaje, tipo )
{
  Swal.fire({
    icon: tipo,
    title: titulo,
    text: mensaje,
    allowOutsideClick: false,
  });
}

function dashboardData( ) 
{
$( '.table-1-valor-activos' ).html( '' );
$( '.table-2-activos-alta' ).html( '' );
$( '.table-3-activos-baja' ).html( '' );

$.ajax({
    url: url + '/dashboard/data',
    type: 'GET',
    dataType: 'json',
})
.done( response =>
{
    if ( response.status == 200 )
    {
    let tabla1 = response.montos;

    if ( tabla1.length == 0 ) 
    {
        $( '.table-1-valor-activos' ).append( '<tr><td>Sin activos</td><td></td></tr>' );
    } 
    else 
    {
        tabla1.forEach( monto => 
        {
        let plantilla = ``;
        if ( monto.monto == 0 ) 
        {
            plantilla = 
            `
            <tr>
                <td>${ monto.tipo }</td>
                <td><span class="badge bg-success dashboardTooltips" data-toggle="tooltip" data-placement="top" title="Sin monto">$0MM</span></td>
            </tr>
            `;
        }
        else
        {
            plantilla = 
            `
            <tr>
                <td>${ monto.tipo }</td>
                <td><span class="badge bg-success dashboardTooltips" data-toggle="tooltip" data-placement="top" title="$${ Number( ( monto.monto ) ) }">$${ Number( ( monto.monto / 1000000 ).toFixed( 2 ) ) }MM</span></td>
            </tr>
            `;
        }
        
        $( '.table-1-valor-activos' ).append( plantilla );
        }); 
    }

    //grafica de dona - variables
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d');
    var donutData =
    {
        labels: response.graficaLabels,
        datasets:
        [
        {
            data: response.graficaValues,
            backgroundColor:
            [
            '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#ffffff',
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

    var areaChartData = {
        labels  : ['Total'],
        datasets: [
          {
            label               : 'Activos inventariados',
            data                : [response.inventariados],
            backgroundColor:
            [
                '#f56954', 
            ],
          }
        ]
      }

    var donut2ChartCanvas = $('#barChart').get(0).getContext('2d')
    var donut2Data =
    {
        labels: [ 'Activos inventariados', 'Activos faltantes'],
        datasets:
        [
        {
            data: [ response.inventariados, response.activos],
            backgroundColor:
            [
                '#3c8dbc', '#d2d6de',
            ],
        }
        ]
    };

    //creación de la grafica
    var donut2Chart = new Chart(donut2ChartCanvas,
    {
        type: 'doughnut',
        data: donut2Data,
        options: donutOptions
    });

    //tabla altas
    let altas = response.altas;

    if ( altas.length == 0 ) 
    {
        $( '.table-2-activos-alta' ).append( '<tr><td>Sin altas</td><td></td><td></td></tr>' );
    } 
    else 
    {
        altas.forEach( item => 
        {
        let plantilla = 
        `
            <tr>
            <td>${ item.Nom_Activo }</td>
            <td>${ item.TS_Create.split( ' ' )[ 0 ]  }</td>
            <td>
                <span class="dashboardTooltips" data-toggle="tooltip" data-placement="top" title="$${ Number( ( parseInt( item.Pre_Compra ) ) ) }">$${ Number( ( parseInt( item.Pre_Compra ) / 1000000 ).toFixed( 2 ) ) }MM</span>
            </td>
            </tr>
        `;

        $( '.table-2-activos-alta' ).append( plantilla );
        });
    }

    //tabla bajas
    let bajas = response.bajas;

    if (bajas.length == 0) 
    {
        $( '.table-3-activos-baja' ).append( '<tr><td>Sin bajas</td><td></td></tr>' );
    }
    else
    {
        bajas.forEach( item => 
        {
        let plantilla = 
        `
            <tr>
            <td>${ item.Nom_Activo }</td>
            <td>${ item.TS_Create.split( ' ' )[ 0 ]  }</td>
            <td>
                <span class="dashboardTooltips" data-toggle="tooltip" data-placement="top" title="$${ Number( ( parseInt( item.Pre_Compra ) ) ) }">$${ Number( ( parseInt( item.Pre_Compra ) / 1000000 ).toFixed( 2 ) ) }MM</span>
            </td>
            </tr>
        `;

        $( '.table-3-activos-baja' ).append( plantilla );
        });
    }

    //mapa
    points = response.points;
    navigator.geolocation.getCurrentPosition( setCoordenadasMapG );

    //tooltips
    $( '.dashboardTooltips' ).tooltip( );


    }
})
.fail( ( ) =>
{
    imprimir( 'Ups..', 'Error al conectar con el servidor2', 'error' );
});
}


function setCoordenadasMapG( position )
{
  if ( points.length > 0 ) 
  {
    let coord = points[ 0 ].GPS.split( ',' );

    lon = coord[ 1 ];
    lat = coord[ 0 ];
  }
  else
  {
    lon = position.coords.longitude;
    lat = position.coords.latitude;
  }

  var globalMap = L.map( 'globalMap' ).setView( [ lat, lon ], 16 );

  globalMap.addControl(new L.Control.Fullscreen());

  L.tileLayer( 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}',
  {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 18,
      id: 'mapbox/streets-v11',
      tileSize: 512,
      zoomOffset: -1,
      accessToken: 'pk.eyJ1IjoiZmluZG15YXNzZXRzIiwiYSI6ImNrZGx5bmU3dTEzbnQycWxqc2wyNjg3MngifQ.P59j7JfBxCpS72-rAyWg0A'
  }).addTo( globalMap );

  points.forEach( point =>
  {
    let coordenadas = point.GPS.split( ',' );
    
    let latitud = coordenadas[ 0 ];
    let longitud = coordenadas[ 1 ];

    L.marker( [ latitud, longitud ] ).addTo( globalMap )
     .bindPopup( point.Desc + '.\n' + point.Nom_Activo + '.\n A.:' + point.nombre)
     .openPopup( );
  });
}

$(document).ready(function( )
{

  dashboardData( );

});