/*
* Admin Alerts Custom Js
*
* Show/hide other icon box on a
*
*/

jQuery(document).ready(function($) {
  $('.other-icon').hide();

  $('#phila_type').change(function(){
    if($('#phila_type').val() == 'Other') {
      $('.other-icon').show();
    } else {
      $('.other-icon').hide();
    }
  });
});
