var url = $('#url').val( );

$( document ).ready( ( ) => 
{
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
          $( '#urlcifrada' ).val( response.url );
        }
        else if ( response.status == 201 )
        {
          alert( response.msg );
          $( '#urlcifrada' ).val( response.url );
        }
        else
        {
          alert( response.msg );
        }
      });

    });
});
