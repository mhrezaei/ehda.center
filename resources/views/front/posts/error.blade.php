@if(!$ajaxRequest)
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-center">
                    @endif
                    <div class="row">
                        <div class="col-xs-12 result-container">
                            <div class="alert alert-danger">
                                {{ $errorMessage }}
                            </div>
                        </div>
                    </div>
                    @if(!$ajaxRequest)
                </div>
            </div>
        </div>
    </div>
@endif
