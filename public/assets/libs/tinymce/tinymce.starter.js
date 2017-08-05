var editor_config = {
	path_absolute : url(),
	selector: '.tinyEditor' ,
	menubar: false ,
//                  theme: 'modern',
	content_css: assets()+'/css/tinyMCE.min.css',
	directionality : 'rtl',
	language: 'fa',
	plugins: "link,table,textcolor,image,directionality,fullscreen,codesample,code",
//	plugins: [
//		"advlist autolink lists link image charmap print preview hr anchor pagebreak",
//		"searchreplace wordcount visualblocks visualchars code fullscreen",
//		"insertdatetime media nonbreaking save table contextmenu directionality",
//		"emoticons template paste textcolor colorpicker textpattern"
//	],
	toolbar: ['insertfile undo redo | bold italic underline strikethrough | copy cut paste removeformat | link unlink inserttable | codesample image fullscreen | code ',
		    'alignleft aligncenter alignright alignjustify | bullist numlist | ltr rtl  | forecolor backcolor forecolorpicker backcolorpicker fontsizeselect'
//		    , 'outdent indent'
	],

	relative_urls: false,

	theme_advanced_buttons1 : "link,unlink" ,

	setup : function(e) {
		e.on('change', function () {
			var function_name = $("#"+this.id).attr('onchange') ;
			window[function_name]();
		});
	},

	file_browser_callback : function(field_name, url, type, win) {
		var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
		var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

		var cmsURL = editor_config.path_absolute + 'manage/filemanager?field_name=' + field_name;
		if (type == 'image') {
			cmsURL = cmsURL + "&type=Images";
		} else {
			cmsURL = cmsURL + "&type=Files";
		}

		tinyMCE.activeEditor.windowManager.open({
			file : cmsURL,
			title : 'Filemanager',
			width : x * 0.8,
			height : y * 0.8,
			resizable : "yes",
			close_previous : "no"
		});
	}

};

var mini_config = {
	path_absolute : url(),
	selector: '.tinyMini' ,
	menubar: false ,
//                  theme: 'modern',
	content_css: assets()+'/css/tinyMCE.min.css',
	directionality : 'rtl',
	language: 'fa',
	plugins: "link,table,textcolor,image,fullscreen,",
	toolbar: ['bold italic underline strikethrough | removeformat | link unlink | alignleft aligncenter alignright alignjustify | fullscreen',
	],

	relative_urls: false,

	theme_advanced_buttons1 : "link,unlink" ,

	file_browser_callback : function(field_name, url, type, win) {
		var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
		var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

		var cmsURL = editor_config.path_absolute + '/filemanager?field_name=' + field_name;
		if (type == 'image') {
			cmsURL = cmsURL + "&type=Images";
		} else {
			cmsURL = cmsURL + "&type=Files";
		}

		tinyMCE.activeEditor.windowManager.open({
			file : cmsURL,
			title : 'Filemanager',
			width : x * 0.8,
			height : y * 0.8,
			resizable : "yes",
			close_previous : "no"
		});
	}

};

tinymce.init(editor_config);
tinymce.init(mini_config);