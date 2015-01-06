jQuery(document).ready(function($) {
  $('.other-icon').hide();

  $('#phila_type').change(function(){
    if($('#phila_type').val() == 'other') {
      $('.other-icon').show();
    } else {
      $('.other-icon').hide();
    }
  });

  /*
  TODO figure out why this doesn't work
  $( ".start-time .rwmb-datetime.hasDatepicker" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
      onClose: function( selectedDate ) {
        console.log('closed');
        $( ".end-time .rwmb-datetime.hasDatepicker" ).datepicker( "option", "minDate", selectedDate );//to
      }
    });
    $( ".end-time .rwmb-datetime.hasDatepicker" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
      onClose: function( selectedDate ) {
        console.log('closed');
        $( ".start-time .rwmb-datetime.hasDatepicker" ).datepicker( "option", "maxDate", selectedDate );//from
      }
    });
    */
});
