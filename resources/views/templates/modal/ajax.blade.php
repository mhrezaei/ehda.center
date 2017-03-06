@include('templates.modal.start' , [
	'modal_id' => $modal_id ,
	'modal_size' => isset($modal_size)? $modal_size : 'lg',
	'form_url' => isset($form_url)? $form_url : 'javascript:void(0)',
	'modal_title' => isset($modal_title)? $modal_title : '',
	'hidden_vars' => isset($hidden_vars)? $hidden_vars : '',
])
	<div class='modal-body'>...</div>
@include('templates.modal.end')