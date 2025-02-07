jQuery(document).on('ready', function() {
    $('#notebook_header_form select, #notebook_header_form input').on('change', function() {
        $(this).closest('form').submit();
    })
})