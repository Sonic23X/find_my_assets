'use strict'

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

$(document).ready(function( )
{
  //URL del servidor
  let url = $('#url').val( );

  //Vista actual
  let actualView = '.home';

  //funciones para la navegación de la barra
  $( '#home' ).click( event =>
  {
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
    //activamos el color
    activeItem( '#historico' );



  });

  $( '#notify' ).click( event =>
  {
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


});
