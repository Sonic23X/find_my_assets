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
      $(`#sucursal_${id}`).html('');
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
      $(`#area_${id}`).html('');
    }
    else
      imprimir( 'Ups...', response.msg, 'error' );
  })
  .fail( ( ) =>
  {
    imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
  });
}

function newTipo(id) 
{
  $('#newTipoIdEmpresa').val(id);
  $('#newTipo').modal('show');
}

function editTipo(id, name) 
{
  $('#editTipoId').val(id);
  $('#editTipoName').val(name);
}

function deleteTipo(id) 
{
  let json = 
  {
    id: id,
  };

  //subir a servidor
  $.ajax({
    url: url + '/empresas/deleteTipo',
    type: 'POST',
    dataType: 'json',
    data: json,
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      imprimir( '¡Hecho!', response.msg, 'success' );
      $(`#tipo_${id}`).html('');
    }
    else
      imprimir( 'Ups...', response.msg, 'error' );
  })
  .fail( ( ) =>
  {
    imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
  });
}

function newCC(id) 
{
  $('#newCCIdEmpresa').val(id);
  $('#newCC').modal('show');
}

function editCC(id, name, codigo) 
{
  $('#editCCId').val(id);
  $('#editCCName').val(name);
  $('#editCCCode').val(codigo);
}

function deleteCC(id) 
{
  let json = 
  {
    id: id,
  };

  //subir a servidor
  $.ajax({
    url: url + '/empresas/deleteCC',
    type: 'POST',
    dataType: 'json',
    data: json,
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      imprimir( '¡Hecho!', response.msg, 'success' );
      $(`#cc_${id}`).html('');
    }
    else
      imprimir( 'Ups...', response.msg, 'error' );
  })
  .fail( ( ) =>
  {
    imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
  });
}

function changeEmpresa(id) 
{
    let json = 
    {
      id: id
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
}

$(document).ready(() =>
{
    $('#saveCompany').click(() => 
    {
      let json = 
      {
        nombre: $('#companyNewName').val(),
      };

      //subir a servidor
      $.ajax({
        url: url + '/empresas/newCompany',
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
          $(`#table_${$('#newSucursalIdEmpresa').val()}_sucursal`).html('');
          
          response.sucursal.forEach(element => 
          {
            let plantilla = 
            `
            <tr id="sucursal_${element.id}">
              <td id="sucursal_name_${element.id}">
                  ${element.Desc}
              </td>
              <td>
                  <a href="#" onClick="editSucursal(${element.id}, '${element.Desc}')"><i class="fas fa-edit"></i></a>
                  <a href="#" onClick="deleteSucursal(${element.id})"><i class="fas fa-times text-danger"></i></a>
              </td>
            </tr>
            `;
            
            $(`#table_${$('#newSucursalIdEmpresa').val()}_sucursal`).append(plantilla);
          });

          $('#newSucursal').modal('hide');
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
          $(`#sucursal_name_${$('#editSucursalId').val()}`).html($('#editSucursalName').val());
          $('#editSucursal').modal('hide');
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
          
          $(`#table_${$('#newAreaIdEmpresa').val()}_area`).html('');
          
          response.area.forEach(element => 
          {
            let plantilla = 
            `
            <tr id="area_${element.id}">
              <td id="area_name_${element.id}">
                  ${element.descripcion}
              </td>
              <td>
                  <a href="#" onClick="editArea(${element.id}, '${element.descripcion}')"><i class="fas fa-edit"></i></a>
                  <a href="#" onClick="deleteArea(${element.id})"><i class="fas fa-times text-danger"></i></a>
              </td>
            </tr>
            `;
            
            $(`#table_${$('#newAreaIdEmpresa').val()}_area`).append(plantilla);
          });

          $('#newArea').modal('hide');
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
          $(`#area_name_${$('#editAreaId').val()}`).html($('#editAreaName').val());
          $('#editArea').modal('hide');
        }
        else
          imprimir( 'Ups...', response.msg, 'error' );
      })
      .fail( ( ) =>
      {
        imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
      });
      
    });

    $('#saveNewTipo').click(() => 
    {
      let json = 
      {
        id: $('#newTipoIdEmpresa').val(),
        nombre: $('#newTipoName').val(),
      };

      //subir a servidor
      $.ajax({
        url: url + '/empresas/newTipo',
        type: 'POST',
        dataType: 'json',
        data: json,
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          imprimir( '¡Hecho!', response.msg, 'success' );
          
          $(`#table_${$('#newTipoIdEmpresa').val()}_tipo`).html('');
          
          response.tipos.forEach(element => 
          {
            let plantilla = 
            `
            <tr id="tipo_${element.id}">
              <td id="tipo_name_${element.id}">
                  ${element.Desc}
              </td>
              <td>
                  <a href="#" onClick="editTipo(${element.id}, '${element.Desc}')"><i class="fas fa-edit"></i></a>
                  <a href="#" onClick="deleteTipo(${element.id})"><i class="fas fa-times text-danger"></i></a>
              </td>
            </tr>
            `;
            
            $(`#table_${$('#newTipoIdEmpresa').val()}_tipo`).append(plantilla);
          });

          $('#newTipo').modal('hide');
        }
        else
          imprimir( 'Ups...', response.msg, 'error' );
      })
      .fail( ( ) =>
      {
        imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
      });
      
    });

    $('#saveEditTipo').click(() => 
    {
      let json = 
      {
        id: $('#editTipoId').val(),
        nombre: $('#editTipoName').val(),
      };

      //subir a servidor
      $.ajax({
        url: url + '/empresas/editTipo',
        type: 'POST',
        dataType: 'json',
        data: json,
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          imprimir( '¡Hecho!', response.msg, 'success' );
          $(`#tipo_name_${$('#editTipoId').val()}`).html($('#editTipoName').val());
          $('#editTipo').modal('hide');
        }
        else
          imprimir( 'Ups...', response.msg, 'error' );
      })
      .fail( ( ) =>
      {
        imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
      });
      
    });

    $('#saveNewCC').click(() => 
    {
      let json = 
      {
        id: $('#newCCIdEmpresa').val(),
        nombre: $('#newCCName').val(),
        codigo: $('#newCCId').val(),
      };

      //subir a servidor
      $.ajax({
        url: url + '/empresas/newCC',
        type: 'POST',
        dataType: 'json',
        data: json,
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          imprimir( '¡Hecho!', response.msg, 'success' );
          
          $(`#table_${$('#newCCIdEmpresa').val()}_cc`).html('');
          
          response.ccs.forEach(element => 
          {
            let plantilla = 
            `
            <tr id="cc_${element.id}">
              <td id="cc_subcuenta_${element.id}">
                  ${element.Subcuenta}
              </td>
              <td id="cc_name_${element.id}">
                  ${element.Desc}
              </td>
              <td>
                  <a href="#" onClick="editCC(${element.id}, '${element.Desc}')"><i class="fas fa-edit"></i></a>
                  <a href="#" onClick="deleteCC(${element.id})"><i class="fas fa-times text-danger"></i></a>
              </td>
            </tr>
            `;
            
            $(`#table_${$('#newCCIdEmpresa').val()}_cc`).append(plantilla);
          });

          $('#newCC').modal('hide');
        }
        else
          imprimir( 'Ups...', response.msg, 'error' );
      })
      .fail( ( ) =>
      {
        imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
      });
      
    });

    $('#saveEditCC').click(() => 
    {
      let json = 
      {
        id: $('#editCCId').val(),
        nombre: $('#editCCName').val(),
        codigo: $('#editCCCode').val(),
      };

      //subir a servidor
      $.ajax({
        url: url + '/empresas/editCC',
        type: 'POST',
        dataType: 'json',
        data: json,
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          imprimir( '¡Hecho!', response.msg, 'success' );
          $(`#cc_name_${$('#editCCId').val()}`).html($('#editCCName').val());
          $(`#cc_subcuenta_${$('#editCCId').val()}`).html($('#editCCCode').val());
          $('#editCC').modal('hide');
        }
        else
          imprimir( 'Ups...', response.msg, 'error' );
      })
      .fail( ( ) =>
      {
        imprimir( 'Ups...', 'Error al conectar con el servidor, intente más tarde', 'error' );
      });
      
    });

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