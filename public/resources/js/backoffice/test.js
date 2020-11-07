$( document ).ready( ( ) => 
{
    
});

var url = $('#url').val( );

function getUrl(  )
{
    let data = 
    {
        email : $( '#email' ).val( ),
        password: $( '#password' ).val( ),
    };

    $.ajax({
        url: url + '/usuarios/generateurl',
        type: 'POST',
        dataType: 'json',
        data: data,
      })
      .done( response =>
      {
        if ( response.status == 200 )
        {
          console.log(response.url);
          $( '#urlcifrada' ).val( response.url );
        }
      });
    


}