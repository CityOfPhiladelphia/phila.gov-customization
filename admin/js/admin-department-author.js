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

  //hide unwanted items from the wordpress menus
  $('.menu-icon-service_post').hide();
  $('a[href^="nav-menus.php"]').hide();
  $('.menu-icon-document').hide();

  //do not allow new categories to be added
  $('#category-adder').hide();
  $('#news_type-adder').hide();

  //$('.add-clone').hide();
  $('#categorychecklist input').attr('disabled', true);

  //don't allow department authors to create new menus
  $('.add-new-menu-action').hide();
  //hide "Appearance" menu
  $('#menu-appearance').hide();
  $('.page-title-action').hide();
  $('#wp-admin-bar-new-content').hide();
  $('.edit-slug').hide();



  var menuIdString = $('#menu-id').text().trim();
  var allMenuIDs = menuIdString.split(" ");
  var match = document.getElementById( allMenuIDs );

  //hide all menu locations
  $('.menu-theme-locations input').parent().css('display', 'none');
  //display menu locations that match current user roles
  for (var i = 0; i < allMenuIDs.length ; i++) {
    var currentMenuId = document.getElementById( allMenuIDs[i] );
    $(currentMenuId).parent().css('display', 'block');
  }

  var menuNameString = $('#menu-name').text().trim();
  var allMenuNames = menuNameString.split(" ");
  //hide all menus from the menu selection dropdown
  $('.manage-menus option').css('display', 'none');

  //show menus that match current user roles
  for (var i = 0; i < allMenuNames.length ; i++) {
    var currentMenuName = allMenuNames[i];
    $( '.manage-menus option:contains("' + currentMenuName + '")').show();
  }
  //add correct menu classes to "nav menu" link
  var currentURL = window.location.pathname;

  if (currentURL.indexOf('nav-menus') > -1){
    $('#menu-posts-department_page').removeClass('wp-not-current-submenu');
    $('#menu-posts-department_page').addClass('wp-has-current-submenu wp-menu-open menu-top');
    $('.wp-submenu-wrap li:last-child').addClass('current');
    $('.menu-icon-department_page').removeClass('wp-not-current-submenu');
    $('.menu-icon-department_page').addClass('wp-has-submenu wp-has-current-submenu wp-menu-open');
  }

  if ( ( typenow == 'news_post') && adminpage.indexOf('post') > -1 ){
    $("#post").validate({
      rules: {
         'post_title' : 'required'
       }
    });
    $( "#title" ).rules( "add", {
      maxlength: 70
    });
    $( "#phila_news_desc" ).rules( "add", {
      maxlength: 255, required: true
    });
  }
  if ( ( typenow == 'attachment') && adminpage.indexOf('post') > -1 ){
    $("#post").validate({
      rules: {
         'post_title' : 'required'
       }
    });
    $( "#attachment_content" ).rules( "add", {
      maxlength: 225, required: true
    });
  }
  if ( ( typenow == 'site-wide-alert') && adminpage.indexOf('post') > -1 ){
    $("#post").validate({
      rules: {
         'post_title' : 'required'
       }
     });
   }
   if ( ( typenow == 'department_page') && adminpage.indexOf('post') > -1 ){
     $("#title").prop('disabled', true);
   }
});
