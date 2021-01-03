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
            if (respuesta.errores.length == 0) {
                alert('Carga con exito');
            }
            else
            {
                alert('Carga con algunos errores');
                respuesta.errores.forEach(element => {
                    $('#errors').append(`<li>${element}</li>`)
                });
            }
        }
        else
            alert('A ocurrido un error desconocido');
        
    })
    .fail( ( ) =>
    {
        alert( 'error al guardar los datos' );
    });
}