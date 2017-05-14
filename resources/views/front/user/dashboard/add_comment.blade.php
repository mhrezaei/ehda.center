@if(setting()->ask('dashboard_comment')->gain())
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-center">
                {!! PostsServiceProvider::showPost('customers-comments') !!}
            </div>
        </div>
    </div>
@endif
