tinymce.init({
    selector: ".texto",
    language: "pt",
    plugins: [
        "advlist autolink lists link charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste emoticons"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | emoticons",
    language_url: './recursos/js/tinymce/langs/pt_BR.js',
    moxiemanager_file_settings: {
    	/* Only list txt files, and remove side navigation. */
		moxiemanager_extensions : 'txt',
		moxiemanager_leftpanel : false
    },
    moxiemanager_image_settings: {
    	/* Scope to different folder, show thumbnails of selected extensions */
    	moxiemanager_title : 'Images',
    	moxiemanager_extensions : 'jpg,png,gif',
    	moxiemanager_rootpath : '/testfiles/testfolder',
    	moxiemanager_view : 'thumbs'
    },    
   setup: function (editor) {
        editor.on('change', function () {
            tinymce.triggerSave();
        });
    }    
});

function inserirTexto2(){
    tinymce.activeEditor.setContent(tinymce.activeEditor.getContent() + '[local]');
}   

