var url = $('#url').val( );
let activosTable = null;

function imprimir ( titulo, mensaje, tipo )
{
  Swal.fire({
    icon: tipo,
    title: titulo,
    text: mensaje,
    allowOutsideClick: false,
  });
}

function changeFile( nodo ) 
{
    $( '#excelFileName' ).html( nodo.files[0].name );

    let formData = new FormData();
    formData.append( 'excel', nodo.files[0] );

    $.ajax(
    {
        url: url + '/carga/readExcel',
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
    })
    .done( response =>
    {
        let respuesta = JSON.parse(response);
        
        if(respuesta.status == 200)
        {
            $('#instructions').html('Resultado de la carga');

            $('.up-start').addClass('d-none');
            $('.up-load').addClass('d-none');
            $('.up-result').removeClass('d-none');

            $('.up-ready').html(respuesta.subidos);
            $('.up-problems').html(respuesta.errores.length);

            if (activosTable != null)
            {
              activosTable.destroy();
              let table = 
              `
                <table class="table table-hover up-ready-table">
                  <thead>
                      <tr>
                          <th scope="col">No. Activo</th>
                          <th scope="col">Activo</th>
                          <th scope="col">Asignación</th>
                          <th scope="col">Cargado</th>
                      </tr>
                  </thead>
                  <tbody class="up-ready-table-content">

                  </tbody>
                </table>
              `;
              $('.up-ready-table-div').html(table);  
            }

            $('.up-problems-table-content').html('');

            respuesta.activos.forEach(element =>
            {
                let plantilla =
                `
                    <tr>
                        <td class="align-middle">
                            ${ element.id_activo }                            
                        </td>
                        <td class="align-middle">
                            <a class="text-dark text-decoration-none" onClick="viewInfo( ${ element.id } )">
                                ${ element.tipo }
                                <br>
                                ${ element.nombre }
                            </a>                         
                        </td>
                        <td class="align-middle">
                            ${ element.usuario }
                        </td>
                        <td class="align-middle">
                            ${ element.fecha }
                        </td>
                    </tr>
                `;

                $('.up-ready-table-content').append(plantilla);
            });

            respuesta.errores.forEach(element => 
            {
                let plantilla =
                `
                    <tr>
                        <td>
                            ${ element.problema }                            
                        </td>
                        <td class="align-middle">
                            ${ element.activo }
                        </td>
                        <td class="align-middle">
                            <a href="#">
                                <i class="fas fa-search"></i>
                            </a>
                        </td>
                    </tr>
                `;

                $('.up-problems-table-content').append(plantilla);
            });

            if (activosTable != null)
                activosTable.destroy();

            activosTable = $('.up-ready-table').DataTable(
            {
                'ordering': false,
                'responsive': true,
                'lengthChange': false,
                'responsive': true,
                'bInfo' : false,
            });
        }
        else
            imprimir('Ups..', 'A ocurrido un error desconocido', 'error');
        
    })
    .fail( ( ) =>
    {
        imprimir('Ups..', 'Error al guardar los datos', 'error');
    });

    nodo.value = "";
    $('#excelFileName').html('Adjuntar plantilla aquí');

}

function navSteps(step) 
{
    switch (step) {
        case 1:
            $('#instructions').html('Obtén y completa la plantilla');
            $('.up-start').removeClass('d-none');
            $('.up-load').addClass('d-none');
            $('.up-result').addClass('d-none');

            $( '.up1-circle' ).css('background', '#e6c84f');
            $( '.up1-label' ).css('color', '#e6c84f');
            $( '.up2-circle' ).css('background', '#6c757d');
            $( '.up2-label' ).css('color', '#6c757d');
            break;
        case 2:
            $('#instructions').html('Ingresa la plantilla de activos');
            $('.up-load').removeClass('d-none');
            $('.up-start').addClass('d-none');
            $('.up-result').addClass('d-none');

            $( '.up2-circle' ).css('background', '#e6c84f');
            $( '.up2-label' ).css('color', '#e6c84f');
            $( '.up1-circle' ).css('background', '#6c757d');
            $( '.up1-label' ).css('color', '#6c757d');
            break;
    }
}

function download() 
{
    window.location.href = url + '/carga/ejemplo';

    $('#instructions').html('Ingresa la plantilla de activos');
    $('.up-load').removeClass('d-none');
    $('.up-start').addClass('d-none');

    $( '.up2-circle' ).css('background', '#e6c84f');
    $( '.up2-label' ).css('color', '#e6c84f');
    $( '.up1-circle' ).css('background', '#6c757d');
    $( '.up1-label' ).css('color', '#6c757d');
}

function viewInfo(id) 
{
    $.ajax(
    {
        url: url + `/inventario/getDraftInfo/${ id }`,
        type: 'GET',
        dataType: 'json',
    })
    .done( response =>
    {
        if ( response.status == 200 )
        {
            let activo = response.activo;
        
            $( '#infoNoActivo' ).val( activo.ID_Activo );
            $( '#infoTipoActivo' ).val( activo.ID_Tipo );
            $( '#infoName' ).val( activo.Nom_Activo );
            $( '#infoSerie' ).val( activo.NSerie_Activo );
            $( '#infocCosto' ).val( activo.ID_CC );
            $( '#infoAsignacion' ).val( activo.User_Inventario );
            $( '#infoEmpresa' ).val( activo.ID_Company );
            $( '#infoSucursal' ).val( activo.ID_Sucursal );
            $( '#infoArea' ).val( activo.ID_Area );
            $( '#infoDesc' ).val( `${ activo.Des_Activo }` );
            if (activo.TS_Update != null) 
                $( '#infoFechaUpdate' ).val( `${ activo.TS_Update.split(' ')[0] }` );
            else
                $( '#infoFechaUpdate' ).val('Sin actualización');
        
            $( '#infoButtonSerie' ).attr( 'data-original-title', response.tooltip );
        
            $( '#infoModal' ).modal( 'show' );
        }

    });
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

$(document).ready(() =>
{
    getInvFormData( );
});