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
@append

@section('html_header')
    <style>
    </style>

    <script>
        $(document).ready(function () {
            $(".file-manager-modal").find('.file-manager-iframe').attr('src', '{{ route('fileManager.index') }}');

            $(".file-manager-modal").modal();
        });
    </script>
@append