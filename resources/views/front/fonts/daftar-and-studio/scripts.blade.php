@section('endOfBody')
    <script>
        let purchaseForm = $('#purchase-form');
        $(document).ready(function () {
            $('.btn-buy-post').click(function (e) {
                e.preventDefault();
                let that = $(this);

                purchaseForm.find('#post_id').val(that.data('postKey'));
                purchaseForm.find('#title').val(that.data('postTitle'));
                purchaseForm.find('#price').val(that.data('postPrice'));

                if(!purchaseForm.is(':visible')){
                    purchaseForm.slideDown();
                }
            });
        });
    </script>
@append