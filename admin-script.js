// admin-script.js

jQuery(document).ready(function($){
    // Initialize WordPress color picker
    $('.color-field').wpColorPicker();

    // On form submission, change the submit button to a loader
    $('.splutter-form').on('submit', function(e) {
        var $button = $(this).find('.splutter-save-button');
        $button.addClass('loading');
        $button.attr('disabled', 'disabled');
    });
});
