
$(document).ready(() =>
{

  /* --- Navbar background animate --- */
  $(window).scroll(() =>
  {

    var posY = window.pageYOffset;

    if(posY > 20)
      $( '.navbar' ).attr('style', 'background: #343a40 !important');
    else
  		$( '.navbar' ).attr('style', 'background: transparent !important');

  });

  /* ---  --- */




});
