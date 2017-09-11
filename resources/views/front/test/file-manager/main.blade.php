@extends('manage.frame.layouts.plane')

@section('body')
    <!-- FileManager Modal -->
    <div class="modal fade file-manager-modal" id="file-manager-modal" role="dialog">

        <div class="modal-dialog">
            <div class="modal-content">
                <button class="btn-close" data-dismiss="modal">
                    <span class="fa fa-times"></span>
                </button>
                <div class="modal-body">
                    <iframe class="file-manager-iframe" frameborder="0"></iframe>
                </div>
            </div>

        </div>
    </div>

    <button type="button" id="btnFeaturedImage" data-file-manager-input="txtFeaturedImage"
            data-file-manager-preview="divFeaturedImage" data-file-manager-callback="alert('fsdlfjdlskj')"
            data-file-manager-multi="1"
            {{--data-file-manager-output-type="pathname"--}}
            class="btn btn-primary">
        {{ trans('forms.button.browse_image') }}
    </button>
    <input id="txtFeaturedImage" type="text" name="featured_image" value="{{ '' }}" style="width: 100%">

    <div id="divFeaturedImage"></div>

@append

@section('html_header')
    {!! Html::style('assets/css/postEditorStyles.min.css') !!}
@append

@section('end-of-body')
    {!! Html::script ('assets/libs/file-manager/file-manager-modal.min.js') !!}
    <script>
        $(document).ready(function () {
            $("#btnFeaturedImage").fileManagerModal('Files', {
                prefix: "{{ route('fileManager.index') }}",
            }).click();
        });
    </script>
@append