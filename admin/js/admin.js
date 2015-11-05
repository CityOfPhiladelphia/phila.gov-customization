/* For all admins */
jQuery(document).ready(function($){

  //Rename Pages to "Information Pages"
  //This is here because of permissions weirdness as well as timing problems
  $('#adminmenuwrap .wp-submenu a').each(function(i) {
    if($(this).attr("href") == "edit.php?post_type=page") {
        $(this).text("Information Page");
      }else if($(this).attr("href") == "post-new.php?post_type=page") {
        $(this).text("Add Information Page");
    }
  });

  //no one can clone rn
  $('#department-content-highlights .add-clone').css('visibility', 'hidden');

  if( $('.misc-pub-attachment input[value*=".pdf"]').val() ) {
    $('.post-type-attachment #categorydiv input').prop( 'disabled', true );
    $('.post-type-attachment #publication_typediv input').prop( 'disabled', true );
  }

  //only modify wp.media if this is a department site, or publication
  if ( (typenow == 'department_page' || typenow == 'document') && adminpage.indexOf('post') > -1 ){
    //make upload tab the default
    wp.media.controller.Library.prototype.defaults.contentUserSetting=false;
    wp.media.controller.Library.prototype.defaults.searchable=false;
    wp.media.controller.Library.prototype.defaults.sortable=false;
  }

  if ( (typenow == 'department_page' || typenow == 'document' || typenow == 'service_post') && adminpage.indexOf('post') > -1 ){
    $("#post").validate({
        rules: {
          'post_title' : 'required'
        }
      });
  }
  if (typenow == 'department_page' && adminpage.indexOf('post') > -1 ){
    $( "#phila_highlight_title" ).rules( "add", {
      maxlength: 20, required: true
    });
    $( "#phila_highlight_image" ).rules( "add", {
     required: true
    });
    $( "#phila_highlight_content_title" ).rules( "add", {
      maxlength: 70, required: true
    });
    $( "#phila_highlight_description" ).rules( "add", {
      maxlength: 255, required: true
    });
  }

});
