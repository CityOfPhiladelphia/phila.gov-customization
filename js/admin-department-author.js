jQuery(document).ready(function($){
  //force top item (in the case of department editors, the only) to be checked all the time
  var required_cat = $('#categorychecklist li:first-child input');
  if( !required_cat.attr('checked')  ) {
    required_cat.attr('checked','checked');
  }

  $('a[href$="nav-menus.php?action=locations"]').hide();
  //$('.manage-menus').hide();

  var menuIdString = $('#menu-id').text();
  var match = document.getElementById( menuIdString );

  //hides the menus from
  $('.menu-theme-locations input').each(function() {
    if ( this == match ){
    }else{
      $(this).parent().css('display', 'none');
    }
  });

  var menuNameString = $('#menu-name').text();
  $( '.manage-menus option' ).not(':contains("' + menuNameString + '")').hide();
  $( '.manage-menus option:contains("' + menuNameString + '")').attr( 'selected', 'selected' );
});
