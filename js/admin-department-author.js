/* only loads in the admin,
for users who do not have the PHILA_ADMIN capability */

jQuery(document).ready(function($){

  "use strict";

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

  //only modify wp.media if this is a department site, or publication
  if ( (typenow == 'department_page' || typenow == 'publication') && adminpage.indexOf('post') > -1 ){
    //make upload tab the default'
    wp.media.controller.Library.prototype.defaults.contentUserSetting=false;
    wp.media.controller.Library.prototype.defaults.searchable=false;
    wp.media.controller.Library.prototype.defaults.sortable=false;
  }

  if ( ( typenow == 'news_post') && adminpage.indexOf('post') > -1 ){

    $( "#title" ).rules( "add", {
     maxlength: 70, required: true
    });
  }

});
