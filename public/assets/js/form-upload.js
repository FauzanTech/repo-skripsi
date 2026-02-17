$(document).ready(function() {
    $('#upload_book_cover').click(function(e) {
      e.preventDefault();
      $('#book_cover').click();
    });
    $('#book_cover').change(function() {
      const file = $('#book_cover')[0].files[0].name;
      $('#book_cover_name').val(file);
    });

    $('#upload_reference_file').click(function(e) {
      e.preventDefault();
      $('#reference_file').click();
    });

    $('#reference_file').change(function(e) {
      const file =  $('#reference_file')[0].files[0].name;
      $('#reference_filename').val(file);
    });
});