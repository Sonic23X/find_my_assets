var url = $('#url').val( );

function changeFile( nodo ) 
{
    $( '#excelFileName' ).html( nodo.files[0].name );

    let formData = new FormData();
    formData.append( 'excel', nodo.files[0] );

    $.ajax(
    {
        url: url + '/test/readExcel',
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
        }
        else
            alert('A ocurrido un error desconocido');
        
    })
    .fail( ( ) =>
    {
        alert( 'Error al guardar los datos' );
    });
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
    window.open(url + '/Carga.xlsx');

    $('#instructions').html('Ingresa la plantilla de activos');
    $('.up-load').removeClass('d-none');
    $('.up-start').addClass('d-none');

    $( '.up2-circle' ).css('background', '#e6c84f');
    $( '.up2-label' ).css('color', '#e6c84f');
    $( '.up1-circle' ).css('background', '#6c757d');
    $( '.up1-label' ).css('color', '#6c757d');
}