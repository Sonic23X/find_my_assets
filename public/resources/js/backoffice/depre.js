var url = $('#url').val( );

function calcular() 
{
    let precio = $('#monto').val();
    let tiempo = $('#tipo-medida').val();
    let tipo = $('#tipo-activo').val();
    let vida = $('#vida-util').val();
    let residual = $('#residual').val();
    let depre = 0;

    switch (tipo) 
    {
        case '1':
            depre = ( precio - residual ) / vida;
            if (tiempo == '1') 
                $('#result').html(`El La depreciación mensual es de ${depre}`)
            else if (tiempo == '2') 
                $('#result').html(`La depreciación anual es de ${depre}`)
            else
                imprimir('Error', 'Error al detectar la medida de tiempo', 'error')
            break;
        default:
            imprimir('Error', 'Error al detectar el tipo de activo', 'error')
            break;
    }
}

function imprimir ( titulo, mensaje, tipo )
{
  Swal.fire({
    icon: tipo,
    title: titulo,
    text: mensaje,
    allowOutsideClick: false,
  });
}

$(document).ready(() => 
{

});