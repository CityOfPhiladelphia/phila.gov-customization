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

    var languages = [];
    //Logic for showing/hiding based on language selected
    
    $eventSelect.on('change', function (e) {
      var lang = $('.rwmb-select-advanced').select2('val');
      var currentClass = '.' + lang;

      switch ( lang ) {

        case ('spanish'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
          languages.push(lang);
        break;

        case ('french'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
          languages.push(lang);
        break;

        case ('chinese'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
          languages.push(lang);
        break;

        case ('korean'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
          languages.push(lang);
        break;

        case ('khmer'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
          languages.push(lang);
        break;

        case ('russian'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
          languages.push(lang);
        break;

        case ('vietnamese'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
          languages.push(lang);
        break;

        case ('french'):
          $("option[value='"+lang+"']").prop('disabled', true);
          $(currentClass).toggle();
          languages.push(lang);
        break;
      }
   });

   $('.phila-lang input').each(function(){
     if( $(this).attr('value') == '' ) {
       $(this).parent().parent().hide();
     }else{

       //When a user comes to a page, items that already have a document shouldn't be selectable
       var disabledLang = $(this).parent().parent();
       var disabledLangClass = $(disabledLang).attr('class').split(' ').pop();
       $("option[value='"+disabledLangClass+"']").prop('disabled', true);
       languages.push(disabledLangClass);
     }
   });

   //Logic for adding/removing a language field. Matches on class names from the options dropdown.
   $('#document-other-langs .rwmb-file-input-remove').click(function() {

     $(this).parent().parent().hide();
     var currentItem = $(this).parent().parent();

     var lastClick = $(currentItem).attr('class').split(' ').pop();

     $.each(languages, function(index, value){
       if(languages[index] === lastClick) {

          $("option[value='"+lastClick+"']").prop('disabled', false);

          languages.splice(index, 1);

          return( false );
         }

    });

   });
  }
});
//We are going to hold off on this for now, and make a plain text field required.
/*
if ( (typenow == 'document') && adminpage.indexOf('post') > -1 ){
  // prevent users from entering more than 300 chars in tinyMCE
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
*/
