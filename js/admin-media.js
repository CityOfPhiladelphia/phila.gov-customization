/* For all admins */
jQuery(document).ready(function($){

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
  /*documents page */
  if ( ( typenow == 'document') && adminpage.indexOf('post') > -1 ){

    $('.postarea').before('<h2>Document Description</h2>');

    $("#post").validate({
      rules: {
       'phila_documents': 'required',
       'post_title' : 'required',
       'mce-tinymce' : 'required'
     }
    });

    /*documents page */
    $('.rwmb-datetime').datepicker();
    $('.rwmb-datetime').datepicker('setDate', new Date());

    var $eventSelect = $('.rwmb-select-advanced');

    $('.phila-lang input').each(function(){
        if( $(this).attr('value') == '' ) {
          $(this).parent().parent().hide();
        }
        if( $('phila-lang input').attr('value') ) {
          console.log('bbom');
        }
    });

    $('.wp-core-ui .phila-lang .button.hidden').removeClass('hidden');

    $eventSelect.on('change', function (e) {
      var lang = $('.rwmb-select-advanced').select2('val');
      var currentClass = '.document-list-' + lang;
      switch ( lang ) {

        case ('spanish'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
        break;

        case ('french'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
        break;

        case ('chinese'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
        break;

        case ('korean'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
        break;

        case ('khmer'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
        break;

        case ('russian'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
        break;

        case ('vietnamese'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
        break;

        case ('french'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
        break;
      }
   });

   $('#document-other-langs .rwmb-file-input-remove').click(function() {
     $(this).parent().parent().hide();

    // $("option[value='spanish']").prop('disabled', false);
   });
  }
});

if ( (typenow == 'document') && adminpage.indexOf('post') > -1 ){
  // prevent users from entering more than 300 chars
  window.onload = function () {
    jQuery('#post-status-info').append('<span class="character-limit"></span>');
    jQuery('.character-limit').hide();
    var editor_char_limit = 300;


    tinymce.activeEditor.on('keyup', function(e) {

      if ( tinyMCE.activeEditor.getContent().length > editor_char_limit ) {
        jQuery('.character-limit').show();
        jQuery('#publishing-action #publish').attr('disabled', 'disabled');
        jQuery('#wp-content-editor-container').css('border', '1px solid #FFA2A2');

      } else {
        jQuery('.character-limit').hide();
        jQuery('#publishing-action #publish').removeAttr('disabled', 'disabled');
        jQuery('#wp-content-editor-container').css('border', 'none');
      }
    });
  }
}
