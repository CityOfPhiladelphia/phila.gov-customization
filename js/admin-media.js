/* For all admins */
jQuery(document).ready(function($){
  if( $('.misc-pub-attachment input[value*=".pdf"]').val() ) {
    $('.post-type-attachment input').prop( 'disabled', true );
  }
});
