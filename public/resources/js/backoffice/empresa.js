var url = $('#url').val( );

function imprimir ( titulo, mensaje, tipo )
{
  Swal.fire({
    icon: tipo,
    title: titulo,
    text: mensaje,
    allowOutsideClick: false,
  });
}

function putImage( node, id )
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

  $( `.img_company_${ id }` ).html( plantillaLoad );

  let formData = new FormData( );

  formData.set( 'id', id );
  formData.append( 'file', imagen );

  //subir a servidor
  $.ajax({
    url: url + '/empresas/setLogo',
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
          <img class="img-fluid" src="${ img }" style="width: 75%" >
      `;

      $( `.img_company_${ id }` ).html( plantilla );
    }
    else
    {
      let plantilla =
      `
        Sin logo
      `;

      $( `.img_company_${ id }` ).html( plantilla );

      imprimir( 'Ups...', response.msg, 'error' );
    }
  })
  .fail( ( ) =>
  {
    $( `.img_company_${ id }` ).html( 'Sin logo' );
  });

}

function update(id) 
{
  let name = $(`#form_name_${id}`).val();
  let fecha_inicio = $(`#form_date_i_${id}`).val();
  let fecha_fin = $(`#form_date_f_${id}`).val();

  let json = 
  {
    id: id,
    name: name,
    start: fecha_inicio,
    end: fecha_fin,
  };

  //subir a servidor
  $.ajax({
    url: url + '/empresas/update',
    type: 'POST',
    dataType: 'json',
    data: json,
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      imprimir( '¡Hecho!', response.msg, 'success' );

      $(`#name_${id}`).html(name);
    }
    else
      imprimir( 'Ups...', response.msg, 'error' );
  })
  .fail( ( ) =>
  {
    imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
  });
}

function finish(id) 
{
  let json = 
  {
    id: id,
  };

  //subir a servidor
  $.ajax({
    url: url + '/empresas/finishPeriod',
    type: 'POST',
    dataType: 'json',
    data: json,
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      imprimir( '¡Hecho!', response.msg, 'success' );

      $(`#form_date_i_${id}`).val('');
      $(`#form_date_f_${id}`).val('');
    }
    else
      imprimir( 'Ups...', response.msg, 'error' );
  })
  .fail( ( ) =>
  {
    imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
  });
}

function newSucursal(id) 
{
  $('#newSucursalIdEmpresa').val(id);
  $('#newSucursal').modal('show');
}

function editSucursal(id, name) 
{
  $('#editSucursalId').val(id);
  $('#editSucursalName').val(name);
  $('#editSucursal').modal('show');
}

function deleteSucursal(id) 
{
  let json = 
  {
    id: id,
  };

  //subir a servidor
  $.ajax({
    url: url + '/empresas/deleteSucursal',
    type: 'POST',
    dataType: 'json',
    data: json,
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      imprimir( '¡Hecho!', response.msg, 'success' );
      location.reload();
    }
    else
      imprimir( 'Ups...', response.msg, 'error' );
  })
  .fail( ( ) =>
  {
    imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
  });
}

function newArea(id) 
{
  $('#newAreaIdEmpresa').val(id);
  $('#newArea').modal('show');
}

function editArea(id, name) 
{
  $('#editAreaId').val(id);
  $('#editAreaName').val(name);
  $('#editArea').modal('show');
}

function deleteArea(id) 
{
  let json = 
  {
    id: id,
  };

  //subir a servidor
  $.ajax({
    url: url + '/empresas/deleteArea',
    type: 'POST',
    dataType: 'json',
    data: json,
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      imprimir( '¡Hecho!', response.msg, 'success' );
      location.reload();
    }
    else
      imprimir( 'Ups...', response.msg, 'error' );
  })
  .fail( ( ) =>
  {
    imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
  });
}

$(document).ready(() =>
{
    $('#saveNewSucursal').click(() => 
    {
      let json = 
      {
        id: $('#newSucursalIdEmpresa').val(),
        nombre: $('#newSucursalName').val(),
      };

      //subir a servidor
      $.ajax({
        url: url + '/empresas/newSucursal',
        type: 'POST',
        dataType: 'json',
        data: json,
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          imprimir( '¡Hecho!', response.msg, 'success' );
          location.reload();
        }
        else
          imprimir( 'Ups...', response.msg, 'error' );
      })
      .fail( ( ) =>
      {
        imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
      });
      
    });

    $('#saveEditSucursal').click(() => 
    {
      let json = 
      {
        id: $('#editSucursalId').val(),
        nombre: $('#editSucursalName').val(),
      };

      //subir a servidor
      $.ajax({
        url: url + '/empresas/editSucursal',
        type: 'POST',
        dataType: 'json',
        data: json,
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          imprimir( '¡Hecho!', response.msg, 'success' );
          location.reload();
        }
        else
          imprimir( 'Ups...', response.msg, 'error' );
      })
      .fail( ( ) =>
      {
        imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
      });
      
    });

    $('#saveNewArea').click(() => 
    {
      let json = 
      {
        id: $('#newAreaIdEmpresa').val(),
        nombre: $('#newAreaName').val(),
      };

      //subir a servidor
      $.ajax({
        url: url + '/empresas/newArea',
        type: 'POST',
        dataType: 'json',
        data: json,
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          imprimir( '¡Hecho!', response.msg, 'success' );
          location.reload();
        }
        else
          imprimir( 'Ups...', response.msg, 'error' );
      })
      .fail( ( ) =>
      {
        imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
      });
      
    });

    $('#saveEditArea').click(() => 
    {
      let json = 
      {
        id: $('#editAreaId').val(),
        nombre: $('#editAreaName').val(),
      };

      //subir a servidor
      $.ajax({
        url: url + '/empresas/editArea',
        type: 'POST',
        dataType: 'json',
        data: json,
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          imprimir( '¡Hecho!', response.msg, 'success' );
          location.reload();
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