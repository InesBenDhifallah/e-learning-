document.addEventListener('DOMContentLoaded', function() {
    var textareas = document.querySelectorAll('.ckeditor');
    for (var i = 0; i < textareas.length; i++) {
        CKEDITOR.replace(textareas[i], {
            toolbar: [ 
                ['Bold', 'Italic', 'Underline'],
                ['NumberedList', 'BulletedList'],
                ['Link', 'Unlink'],
                ['Image', 'Table'],
                ['Format', 'Font', 'FontSize'],
                ['TextColor', 'BGColor'],
                ['Maximize']
            ],
            language: 'fr',
            height: '400px'
        });
    }
}); 