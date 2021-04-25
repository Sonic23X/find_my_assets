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
    formData.append( 'sendEmail',  $('#emailCheck').is(":checked") );
    formData.append( 'excel', nodo.files[0] );

    $('.up-content').addClass('d-none');
    $('.up-loading').removeClass('d-none');

    $.ajax(
    {
        url: url + '/usuarios/carga',
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
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo Electronico</th>
                      </tr>
                  </thead>
                  <tbody class="up-ready-table-content">

                  </tbody>
                </table>
              `;
              $('.up-ready-table-div').html(table);  
            }

            $('.up-problems-table-content').html('');

            respuesta.usuarios.forEach(element =>
            {
                let plantilla =
                `
                    <tr>
                        <td class="align-middle">
                            ${ element.nombre }                            
                        </td>
                        <td class="align-middle">
                            ${ element.correo }                      
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
                            ${ element.usuario }
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

            $('.up-loading').addClass('d-none');
            $('.up-content').removeClass('d-none');
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
            $('#instructions').html('Ingresa la plantilla de usuarios');
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
    window.location.href = url + '/usuarios/ejemplo';

    $('#instructions').html('Ingresa la plantilla de usuarios');
    $('.up-load').removeClass('d-none');
    $('.up-start').addClass('d-none');

    $( '.up2-circle' ).css('background', '#e6c84f');
    $( '.up2-label' ).css('color', '#e6c84f');
    $( '.up1-circle' ).css('background', '#6c757d');
    $( '.up1-label' ).css('color', '#6c757d');
}


$(document).ready(() =>
{

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