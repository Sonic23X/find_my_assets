//variables globales
var url = $('#url').val( );
var lon;
var lat;
var globalMap;
var markersLayer = new L.LayerGroup();
var donutChart;
var donut2Chart;

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
            //Map filters
            response.tipos.forEach( element =>
            {
                $( '#tiposActivo' ).append( `<option value="${element.id}">${element.Desc}</option>` );
            });

            response.cc.forEach( element =>
            {
                $( '#ccActivos' ).append( `<option value="${element.id}">${element.Desc}</option>` );
            });

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
            donutChart = new Chart(donutChartCanvas,
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

            var donut2ChartCanvas = $('#barChart').get(0).getContext('2d');
            let total_activos = parseInt(response.inventariados) + parseInt(response.activos);
            let porcentaje_inv = Math.round((parseInt(response.inventariados) / total_activos) * 100);
            let porcentaje_sin = Math.round((parseInt(response.activos) / total_activos) * 100);

            var donut2Data =
            {
                labels: [ `Activos inventariados: ${porcentaje_inv}%`, `Activos faltantes: ${porcentaje_sin}%`],
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
            donut2Chart = new Chart(donut2ChartCanvas,
            {
                type: 'doughnut',
                data: donut2Data,
                options: donutOptions
            });

            $('#periodoInventario').html(response.periodo);

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
                    <td>${ item.TS_Delete.split( ' ' )[ 0 ]  }</td>
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

function mapFilter() 
{
    let data =
    {
        tipo: $('#tiposActivo').val(),
        cc: $('#ccActivos').val(),
        cantidad: $('#numActivos').val(),
        busqueda: $('#nameActivo').val(),
    };

    markersLayer.clearLayers();

    $.ajax({
        url: url + '/dashboard/map',
        type: 'POST',
        dataType: 'json',
        data: data,
    })
    .done( response =>
    {
        response.points.forEach( point =>
        {
            let coordenadas = point.GPS.split( ',' );
              
            let latitud = coordenadas[ 0 ];
            let longitud = coordenadas[ 1 ];
          
            let marker = L.marker( [ latitud, longitud ] )
                            .bindPopup( point.Desc + '.\n' + point.Nom_Activo + '.\n A.:' + point.nombre)
                            .openPopup( );
          
            markersLayer.addLayer(marker);
          
         });
          
        markersLayer.addTo(globalMap);
    })
    .fail( ( XMLHttpRequest, textStatus, errorThrown ) =>
    { 
        imprimir( 'Ups..', 'Error al conectar con el servidor', 'error' );
    });
}

function dashFilter() 
{
    let data =
    {
        tipo: $('#tiposActivo').val(),
        cc: $('#ccActivos').val(),
    };

    markersLayer.clearLayers();
    donut2Chart.destroy();
    donutChart.destroy();

    $.ajax({
        url: url + '/dashboard/filter',
        type: 'POST',
        dataType: 'json',
        data: data,
    })
    .done( response =>
    {
        
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
        donutChart = new Chart(donutChartCanvas,
        {
            type: 'doughnut',
            data: donutData,
            options: donutOptions
        });

        var areaChartData = {
            labels  : ['Total'],
            datasets: 
            [
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

        var donut2ChartCanvas = $('#barChart').get(0).getContext('2d');
        let total_activos = parseInt(response.inventariados) + parseInt(response.activos);
        let porcentaje_inv = Math.round((parseInt(response.inventariados) / total_activos) * 100);
        let porcentaje_sin = Math.round((parseInt(response.activos) / total_activos) * 100);

        porcentaje_inv = (isNaN(porcentaje_inv)) ? '0' : porcentaje_inv;
        porcentaje_sin = (isNaN(porcentaje_sin)) ? '0' : porcentaje_sin;

        var donut2Data;
        if (response.inventariados == 0 && response.activos == 0) 
        {
            donut2Data =
            {
                labels: [ `Activos inventariados: ${porcentaje_inv}%`, `Activos faltantes: ${porcentaje_sin}%`],
                datasets:
                [
                    {
                        data: [ 0, 100 ],
                        backgroundColor:
                        [
                            '#3c8dbc', '#d2d6de',
                        ],
                    }
                ]
            };
        }
        else if ( response.inventariados != 0 && response.activos == 0 )
        {
            donut2Data =
            {
                labels: [ `Activos inventariados: ${porcentaje_inv}%`, `Activos faltantes: ${porcentaje_sin}%`],
                datasets:
                [
                    {
                        data: [ response.inventariados, 0 ],
                        backgroundColor:
                        [
                            '#3c8dbc', '#d2d6de',
                        ],
                    }
                ]
            };
        }
        else if ( response.inventariados == 0 && response.activos != 0 )
        {
            donut2Data =
            {
                labels: [ `Activos inventariados: ${porcentaje_inv}%`, `Activos faltantes: ${porcentaje_sin}%`],
                datasets:
                [
                    {
                        data: [ 0, response.activos ],
                        backgroundColor:
                        [
                            '#3c8dbc', '#d2d6de',
                        ],
                    }
                ]
            };
        }
        else
        {
            donut2Data =
            {
                labels: [ `Activos inventariados: ${porcentaje_inv}%`, `Activos faltantes: ${porcentaje_sin}%`],
                datasets:
                [
                    {
                        data: [ response.inventariados, response.activos ],
                        backgroundColor:
                        [
                            '#3c8dbc', '#d2d6de',
                        ],
                    }
                ]
            };
        }

        //creación de la grafica
        donut2Chart = new Chart(donut2ChartCanvas,
        {
            type: 'doughnut',
            data: donut2Data,
            options: donutOptions
        });

        $('#periodoInventario').html(response.periodo);

        //tabla altas
        let altas = response.altas;
        $( '.table-2-activos-alta' ).html( '' );

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
        $( '.table-3-activos-baja' ).html( '' );

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

        response.points.forEach( point =>
        {
            let coordenadas = point.GPS.split( ',' );
              
            let latitud = coordenadas[ 0 ];
            let longitud = coordenadas[ 1 ];
          
            let marker = L.marker( [ latitud, longitud ] )
                            .bindPopup( point.Desc + '.\n' + point.Nom_Activo + '.\n A.:' + point.nombre)
                            .openPopup( );
          
            markersLayer.addLayer(marker);
          
         });
          
        markersLayer.addTo(globalMap);

        $('#numActivos').val(10);
    })
    .fail( ( XMLHttpRequest, textStatus, errorThrown ) =>
    { 
        imprimir( 'Ups..', 'Error al conectar con el servidor', 'error' );
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
  
  markersLayer.clearLayers();

  globalMap = L.map( 'globalMap' ).setView( [ lat, lon ], 16 );

  globalMap.addControl(new L.Control.Fullscreen());

  L.tileLayer( 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}',
  {
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

    let marker = L.marker( [ latitud, longitud ] )
                    .bindPopup( point.Desc + '.\n' + point.Nom_Activo + '.\n A.:' + point.nombre)
                    .openPopup( );

    markersLayer.addLayer(marker);

  });

  markersLayer.addTo(globalMap);
}

$(document).ready(function( )
{

    dashboardData( );

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

});