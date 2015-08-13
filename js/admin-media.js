/* For all admins */
jQuery(document).ready(function($){

  if( $('.misc-pub-attachment input[value*=".pdf"]').val() ) {
    $('.post-type-attachment #categorydiv input').prop( 'disabled', true );
    $('.post-type-attachment #publication_typediv input').prop( 'disabled', true );
  }

  //make upload tab the default
  wp.media.controller.Library.prototype.defaults.contentUserSetting=false;
  wp.media.controller.Library.prototype.defaults.searchable=false;
  wp.media.controller.Library.prototype.defaults.sortable=false;

  //add delete all button to files page
  $('.rwmb-uploaded').append('<button class="remove-all button">Delete All Files</button>');

  // Delete all files via Ajax
  $( '.remove-all' ).on( 'click', function () {
    if (confirm('Delete all files?')) {
    // Save it!

    var $this = $( this ),
      $parent = $this.parents( 'li' ),
      $container = $this.closest( '.rwmb-uploaded' ),
      data = {
        action       : 'rwmb_delete_file',
        _ajax_nonce  : $container.data( 'delete_nonce' ),
        post_id      : $( '#post_ID' ).val(),
        field_id     : $container.data( 'field_id' ),
        attachment_id: $this.data( 'attachment_id' ),
        force_delete : $container.data( 'force_delete' )
      };

      $('.rwmb-uploaded').addClass('transitionend webkitTransitionEnd otransitionend').children('li').remove();

      $('html,body').animate({
        scrollTop: $("#publication-meta").offset().top
      });

      $.post( ajaxurl, data, function ( r ) {
        if ( !r.success ) {
          alert( r.data );
          return;
        }
      }, 'json' );

      return false;

    }else {

      return false;
    }
  });

  /*publications page */

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

  $eventSelect.on("change", function (e) {
    var lang = $(".rwmb-select-advanced").select2("val");
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

});
