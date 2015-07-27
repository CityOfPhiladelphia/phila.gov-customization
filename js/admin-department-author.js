/* only loads in the admin,
for users who do not have the PHILA_ADMIN capability */

jQuery(document).ready(function($){
  //force top category to be checked all the time
  var required_cat = $('#categorychecklist li:first-child input');
  if( !required_cat.attr('checked')  ) {
    required_cat.attr('checked','checked');
  }
  //hide locations tab on nav-menus
  $('a[href$="nav-menus.php?action=locations"]').hide();

  //don't allow department authors to create new menus
  $('.add-new-menu-action').hide();
  //hide "Appearance" menu
  $('#menu-appearance').hide();

  var menuIdString = $('#menu-id').text();
  var match = document.getElementById( menuIdString );

  //hides other menu locations that do not match this menu ID
  $('.menu-theme-locations input').each(function() {
    if ( this == match ){
    }else{
      $(this).parent().css('display', 'none');
    }
  });

  var menuNameString = $('#menu-name').text();
  //hide other menus from the menu selection dropdown
  $( '.manage-menus option' ).not(':contains("' + menuNameString + '")').hide();
  $( '.manage-menus option:contains("' + menuNameString + '")').attr( 'selected', 'selected' );

  //add correct menu classes to "nav menu" link
  var currentURL = window.location.pathname;

  if (currentURL.indexOf('nav-menus') > -1){
    $('#menu-posts-department_page').removeClass('wp-not-current-submenu');
    $('#menu-posts-department_page').addClass('wp-has-current-submenu wp-menu-open menu-top');
    $('.wp-submenu-wrap li:last-child').addClass('current');
    $('.menu-icon-department_page').removeClass('wp-not-current-submenu');
    $('.menu-icon-department_page').addClass('wp-has-submenu wp-has-current-submenu wp-menu-open');
  }

});
