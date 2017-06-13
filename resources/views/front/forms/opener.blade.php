<div class="tForms">

    {!! Form::open([
        'id' => isset($id) ? $id : 'frm'.rand(1,5000) ,
        'url' => isset($url)? url($url) : '#' ,
        'method' => isset($method)? $method : 'post' ,
        'files' => isset($files)? $files : 'false' ,
        'class' => isset($class)? "form-horizontal $class" : 'form-horizontal ' ,
        'no-validation' => isset($no_validation)? $no_validation : '0' ,
        'onchange' => isset($onchange)? $onchange : '' ,
        'no-ajax' => isset($no_ajax)? $no_ajax : '0' ,
    ]) !!}

    @if(isset($title))
        <div class="title">
            {{$title}}...
        </div>
    @endif

    @if(0) {{-- just to avoid annying 'div-not-closed' error! --}}
        </div>
    @endif