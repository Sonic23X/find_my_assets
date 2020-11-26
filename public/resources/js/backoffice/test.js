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

var tabla = null;


function getUserTableData(  ) 
{
  let base =
  `
    <thead>
      <tr>
          <th scope="col">Nombre</th>
          <th scope="col">Email</th>
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
        searching: true,
        bLengthChange: false,
        pageLength: 5,
        language: spanish,
      });
    }
    else
    {
      imprimir( 'Ups..', 'Error al obtener la información del servidor', 'error' );
    }
  });
}

$( document ).ready( ( ) => 
{

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
});
