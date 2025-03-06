CKEDITOR.editorConfig = function(config) {
    config.language = 'fr';
    config.height = '400px';
    config.removePlugins = 'elementspath,resize';
    config.allowedContent = true;
    
    config.toolbar = [
        ['Bold', 'Italic', 'Underline', 'Strike'],
        ['NumberedList', 'BulletedList'],
        ['Link', 'Unlink'],
        ['Image', 'Table'],
        ['Format', 'Font', 'FontSize'],
        ['TextColor', 'BGColor'],
        ['Maximize']
    ];

    // Désactiver le chargement automatique de config.js
    config.customConfig = '';
}; 