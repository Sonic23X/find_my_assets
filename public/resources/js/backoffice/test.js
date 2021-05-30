var url = $('#url').val( );

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

function imprimir ( titulo, mensaje, tipo )
{
  Swal.fire({
    icon: tipo,
    title: titulo,
    text: mensaje,
    allowOutsideClick: false,
  });
}

var tabla = null;

function getUserTableData(  ) 
{
  let base =
  `
    <thead>
      <tr>
          <th scope="col">Nombre</th>
          <th scope="col">Email</th>
          <th scope="col">Emails enviados</th>
          <th>#</th>
      </tr>
    </thead>
    <tbody class="table-users-items">

    </tbody>
  `;

  $( '.table-users' ).html( base );

  $.ajax({
    url: url + '/usuarios/data',
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      let usuarios = response.data;

      $( '.table-users-items' ).html( '' );
       
      usuarios.forEach( ( usuario, i ) =>
      {

        let typePlantilla =
        `
          <tr>
            <td class="align-middle">
              ${ usuario.nombre } ${ usuario.apellidos }
            </td>
            <td class="align-middle">
              ${ usuario.email }
            </td>
            <td class="align-middle">
              ${ usuario.envios }
            </td>
            <th>
              <div class="dropdown">
                <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Opciones
                </button>
                <div class="dropdown-menu text-center" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" href="#" onClick="editUser( ${ usuario.id_usuario } )">Editar</a>
                  <a class="dropdown-item" href="#" onClick="deleteUser( ${ usuario.id_usuario } )">Borrar</a>
                  <a class="dropdown-item" href="#" onClick="sendEmail( ${ usuario.id_usuario } )">Reenviar correo</a>
                </div>
              </div>
            </th>
          </tr>
        `;

        $( '.table-users-items' ).append( typePlantilla );

      });

      //borramos la tabla si existe
      if ( tabla != null )
        tabla.destroy();

      //creamos la tabla dinamica
      tabla = $( '.table-users' ).DataTable(
      {
        bInfo: false,
        bLengthChange: false,
        pageLength: 10,
        language: spanish,
      });
    }
    else
    {
      imprimir( 'Ups..', 'Error al obtener la información del servidor', 'error' );
    }
  });
}

function getCCs() 
{
  $.ajax({
    url: url + '/usuarios/ccs',
    type: 'GET',
    dataType: 'json',
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {     
      response.data.forEach( ( cc, i ) =>
      {

        let typePlantilla =
        `
          <option value="${cc.id}">${cc.Subcuenta} - ${cc.Desc}</option>
        `;

        $( '#ccUserNew' ).append( typePlantilla );
        $( '#ccUserEdit' ).append( typePlantilla );
      });

    }
    else
    {
      imprimir( 'Ups..', 'Error al obtener la información del servidor', 'error' );
    }
  });
}

function editUser( id ) 
{
  localStorage.setItem( 'user', id );

  $.ajax(
  {
    url: url + '/usuarios/usuario',
    type: 'POST',
    dataType: 'json',
    data: { id: id },
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      $( '#eNombre' ).val( response.data.nombre );
      $( '#eApellidos' ).val( response.data.apellidos );
      $( '#eEmail' ).val( response.data.email );
      $('#ccUserEdit').val(response.data.id_cc)

      $( '#editUserModal' ).modal( 'show' );
    }
    else
    {
      imprimir( 'Error', response.msg, 'error' );
    }
  });

  //buscamos la información y la colocamos en el modal
}

function deleteUser( id ) 
{
  $.ajax(
  {
    url: url + '/usuarios/delete',
    type: 'POST',
    dataType: 'json',
    data: { id: id },
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      getUserTableData( );
      imprimir( '¡Hecho!', response.msg, 'success' );
    }
    else
    {
      imprimir( 'Error', response.msg, 'error' );
    }
  });
}

function sendEmail( id ) 
{
  $.ajax(
  {
    url: url + '/usuarios/sendEmail',
    type: 'POST',
    dataType: 'json',
    data: { id: id },
  })
  .done( response =>
  {
    if ( response.status == 200 )
    {
      imprimir( '¡Hecho!', response.msg, 'success' );

      getUserTableData();
    }
    else
    {
      imprimir( 'Error', response.msg, 'error' );
    }
  });
}

$( document ).ready( ( ) => 
{

    getCCs();

    getUserTableData( );

    $( '#registro' ).submit( event => 
    {
      event.preventDefault( );

      let data = 
      {
          nombre: $( '#nombre' ).val( ),
          apellidos: $( '#apellidos' ).val( ),
          email: $( '#email' ).val( ),
          password: $( '#password' ).val( ),
          cc: $('#ccUserNew').val(),
          sendMail: $('#emailCheck').is(":checked"),
      };

      $.ajax(
      {
        url: url + '/usuarios/generateurl',
        type: 'POST',
        dataType: 'json',
        data: data,
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          getUserTableData( );

          imprimir( '¡Hecho!', response.msg, 'success' );

          //cerramos modal
          $( '#newUserModal' ).modal( 'hide' );

          $( '#nombre' ).val( '' );
          $( '#apellidos' ).val( '' );
          $( '#email' ).val( '' );
          $( '#password' ).val( '' );
        }
        else
        {
          imprimir( 'Error', response.msg, 'error' );
        }
      });
      
    });

    $( '#actualizar' ).submit( event => 
    {
      event.preventDefault( );

      let data = 
      {
          id: localStorage.getItem( 'user' ),
          nombre: $( '#eNombre' ).val( ),
          apellidos: $( '#eApellidos' ).val( ),
          email: $( '#eEmail' ).val( ),
          cc: $('#ccUserEdit').val(),
      };

      $.ajax(
      {
        url: url + '/usuarios/actualizar',
        type: 'POST',
        dataType: 'json',
        data: data,
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          getUserTableData( );

          imprimir( '¡Hecho!', response.msg, 'success' );

          //cerramos modal
          $( '#editUserModal' ).modal( 'hide' );
        }
        else
        {
          imprimir( 'Error', response.msg, 'error' );
        }
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
