CKEDITOR.editorConfig = function(config) {
    // Configuration de base
    config.language = 'fr';
    config.height = 400;
    config.skin = 'moono-lisa';
    config.removePlugins = 'elementspath,about';
    config.allowedContent = true;
    config.enterMode = CKEDITOR.ENTER_BR;
    config.forcePasteAsPlainText = true;
    
    // Configuration des chemins
    config.baseHref = '/';
    config.contentsCss = '/bundles/fosckeditor/contents.css';
    
    // Configuration de la barre d'outils simplifiée
    config.toolbar = [
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
        { name: 'links', items: ['Link', 'Unlink'] },
        { name: 'insert', items: ['Image', 'Table', 'HorizontalRule'] },
        { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
        { name: 'colors', items: ['TextColor', 'BGColor'] },
        { name: 'tools', items: ['Maximize'] }
    ];

    // Configuration des plugins
    config.extraPlugins = 'uploadimage,image2';
    config.removeDialogTabs = 'image:advanced;link:advanced';
    
    // Configuration du chargement
    config.startupMode = 'wysiwyg';
    config.startupFocus = false;
    config.autoGrow_minHeight = 200;
    config.autoGrow_maxHeight = 600;
    
    // Suppression de la configuration des URLs d'upload car elles sont gérées par Symfony
    delete config.filebrowserUploadUrl;
    delete config.uploadUrl;
    delete config.imageUploadUrl;
};