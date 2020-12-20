var url = $('#url').val( );
var newTable = null;
var conciliarTable = null;
var procesoCTable = null;
var procesoWTable = null;
var inventarioTable = null;

var InvActualView = '.inv-news-home';
var InvPreviewView = '';

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

$( '#clp' ).on(
{
  'focus': function( event ) 
  {
    $( event.target ).select( );
  },
  
  'keyup': function( event ) 
  {
    $( event.target ).val( function( index, value ) 
    {
      return value.replace(/\D/g, "")
        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
    });
  }
});

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
    clp: $( '#clp' ).val( ).replace( ',', '' ),
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
      $( '#ciDesc' ).val( activo.Des_Activo );

      $( '#ciButtonSerie' ).attr( 'data-original-title', response.tooltip );

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

      $( '.conciliar-cc-new' ).html( newA.cc );
      $( '.conciliar-cc-old' ).html( oldA.cc );

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

  $( '.conciliar-new' ).html( '' );
  $( '.conciliar-old' ).html( '' );

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
  $( '.iCC' ).html( );

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

      let cc = response.cc;

      cc.forEach( ( ccUnico , i ) =>
      {

        let typePlantilla =
        `
          <option value="${ ccUnico.id }">${ ccUnico.Desc }</option>
        `;

        $( '.iCC' ).append( typePlantilla );

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

      let areas = response.areas;
      
      areas.forEach( ( area , i ) =>
      {

        let typePlantilla =
        `
          <option value="${ area.id }">${ area.descripcion }</option>
        `;

        $( '.iArea' ).append( typePlantilla );

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
        <th scope="col"></th>
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
            <td class="align-middle">
              <input type="checkbox" name="select_${ activo.id }" onClick="downInvCheckbox( this )" class="newInvCheck">
            </td>
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

function downInvCheckbox( node )
{
  let num = 0;

  $( '.newInvCheck:checked' ).each(function()
  {
    num++;
  });

  if ( num > 0)
    $( '.delete-button-inv' ).removeClass( 'd-none' );
  else
    $( '.delete-button-inv' ).addClass( 'd-none' );
}

function multipleInvDelete( )
{
  let data = [ ];

  $( '.newInvCheck:checked' ).each(function( )
  {
    let getId = this.name.split( '_' )[ 1 ];
    data.push( getId );
  });

  Swal.fire({
    icon: 'question',
    title: 'Atención',
    text: '¿Estas seguro de eliminar estos elementos del sistema?',
    allowOutsideClick: false,
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#5cb85c',
    confirmButtonText: 'Eliminar',
    cancelButtonText: 'Cancelar',
  })
  .then((result) => 
  {
    if ( result.isConfirmed )
    {

      let json = 
      {
        items: data,
      };

      $.ajax({
        url: url + '/inventario/deleteNews',
        type: 'POST',
        dataType: 'json',
        data: json,
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {    
          Swal.fire(
          {
            title: '¡Listo!',
            text: 'El/Los activo(s) han sido dados de baja exitosamente',
            icon: 'success',
            confirmButtonColor: '#5cb85c',
          })
          .then( result =>
          {
            $( '.delete-button-inv' ).addClass( 'd-none' );

            getNewItems( );
          });
        }
      });
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
      $( '.new-image-right' ).html( '<i class="fas fa-spinner fa-spin"></i>' );
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
      $( '#newDesc' ).val( activo.Des_Activo );

      $( '#newButtonSerie' ).attr( 'data-original-title', response.tooltip );

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
      $( '#iDesc' ).val( activo.Des_Activo );

      if ( details == 1 )
        $( '.modalProcessButton' ).removeClass( 'd-none' );
      else
        $( '.modalProcessButton' ).addClass( 'd-none' );

      $( '.process-image-front' ).html( '<i class="fas fa-spinner fa-spin"></i>' );
      $( '.process-image-left' ).html( '<i class="fas fa-spinner fa-spin"></i>' );
      $( '.process-image-right' ).html( '<i class="fas fa-spinner fa-spin"></i>' );

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

  $( 'textarea[ name="infoDesc" ]' ).val( 'asd' );

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
      $( '#infoDesc' ).val( `${ activo.Des_Activo }` );

      $( '#infoButtonSerie' ).attr( 'data-original-title', response.tooltip );

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

$(document).ready(function( )
{
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
        switch ( InvActualView )
        {
        case '.inv-news-confirm':
            InvActualView = '.inv-news-home';
            $( '.inv-news-confirm' ).addClass( 'd-none' );
            $( '.inv-buttons' ).removeClass( 'd-none' );
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

    $( '#invFEmpresa' ).change( event =>
    {
        let data = 
        {
            empresa: $( '#invFEmpresa' ).val( ),
        };

        //buscamos el codigo en la BDD
        $.ajax({
            url: url + '/inventario/sucursales',
            type: 'POST',
            dataType: 'json',
            data: data
        })
        .done( response =>
        {
            if (response.status == 200)
            {
                $( '#invFSucursal' ).html( '' );
                $( '#invFSucursal' ).append( '<option value="">Todas</option>' );
                $( '#invFArea' ).html( '' );
                $( '#invFArea' ).append( '<option value="">Todas</option>' );

                response.sucursales.forEach( ( sucursal , i ) =>
                {

                let typePlantilla =
                `
                    <option value="${ sucursal.id }">${ sucursal.Desc }</option>
                `;

                $( '#invFSucursal' ).append( typePlantilla );

                });

                response.areas.forEach( ( area , i ) =>
                {

                let typePlantilla =
                `
                    <option value="${ area.id }">${ area.descripcion }</option>
                `;

                $( '#invFArea' ).append( typePlantilla );

                });
            }
            else
            {
                imprimir( 'Ups..', response.msg, 'error' );
            }
        });
    });
});