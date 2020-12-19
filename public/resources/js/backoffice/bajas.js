var url = $('#url').val( );
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
      $( '#downDesc' ).val( activo.Des_Activo );

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
    getInvFormData( );
    down( );

    $( '#down-select' ).change( (event) =>
    {
        $( '.motivo-down-form' ).removeClass( 'd-none' );
    });

    $( '#downEmpresa' ).change( event =>
    {
        let data = 
        {
            empresa: $( '#downEmpresa' ).val( ),
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
                $( '#downSucursal' ).html( '' );
                $( '#downSucursal' ).append( '<option value="">Todas</option>' );
                $( '#downArea' ).html( '' );
                $( '#downArea' ).append( '<option value="">Todas</option>' );

                response.sucursales.forEach( ( sucursal , i ) =>
                {

                    let typePlantilla =
                    `
                        <option value="${ sucursal.id }">${ sucursal.Desc }</option>
                    `;

                    $( '#downSucursal' ).append( typePlantilla );

                });

                response.areas.forEach( ( area , i ) =>
                {

                    let typePlantilla =
                    `
                        <option value="${ area.id }">${ area.descripcion }</option>
                    `;

                    $( '#downArea' ).append( typePlantilla );

                });
            }
            else
            {
                imprimir( 'Ups..', response.msg, 'error' );
            }
        });

    });

});