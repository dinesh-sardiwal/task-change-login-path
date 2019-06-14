(function ($, Drupal) {
  $('form').submit( function(e){
    if ( !confirm('Are you sure you want to change login path ?') ) {
      e.preventDefault();
    }
  });
})(jQuery, Drupal);
