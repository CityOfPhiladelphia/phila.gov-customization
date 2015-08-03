/* For all admins */
jQuery(document).ready(function($){
  if( $('.misc-pub-attachment input[value*=".pdf"]').val() ) {
    $('.post-type-attachment #categorydiv input').prop( 'disabled', true );
    $('.post-type-attachment #publication_typediv input').prop( 'disabled', true );
  }

  //add delete all button to files page
  $('.rwmb-uploaded').append('<button class="remove-all button">Delete All Files</button>');
  //$('.rwmb-file_advanced-wrapper').append('<button class="phila-modal button">Phila model</button>');

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
    }
  });
});
