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

$(document).ready(() =>
{
    
});