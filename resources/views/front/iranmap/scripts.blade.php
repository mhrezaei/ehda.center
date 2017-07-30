@section('endOfBody')
    {!! Html::script ('assets/js/iranmap.js') !!}
    <script type="text/javascript">
        var states = [
                @foreach($states as $state)
            {
                title: '{{ $state->title }}',
                active: {{ $state->domain->slug ? 'true' : 'false' }},
                link: "{{ addSubDomain(url(''), $state->domain->slug) }}"
            },
            @endforeach
        ];

        var labels = {
            selectState: '{{ trans('front.select_your_intended_state') }}',
        };
        $(function () {
            $('.iran-map .province path').attr('disabled', 'disabled');
            $.each(states, function (index, state) {
                var target = $('.iran-map .province path[data-persian-name="' + state.title + '"]');
                if (target.length) {
                    if (state.active) {
                        target.removeAttr('disabled');
                        target.attr('data-src', state.link);
                    }
                }
            });
            $('.iran-map .province path').click(function () {
                var that = $(this);
                var province = that.attr('data-name');

                if (!that.attr('disabled')) {
                    selectProvince(province);
                }

            }).each(function () {
                var that = $(this);
                var province = that.attr('data-name');

                if (that.attr('disabled')) {
                    $('.states-list li.' + province + ' a').attr('disabled', 'disabled');
                }
            });
            $('.states-list a').click(function (e) {
                e.preventDefault();
                var province = $(this).parent().attr('class');
                console.log(province)
                var provinceName = $(this).html();
                if (!$('.iran-map path.' + province).attr('disabled')) {
                    selectProvince(province);
                    $('.states-list').find('.active').removeClass('active');
                    $(this).addClass('active');
                    $('.states-list').first().fadeOut();
                    $('.choose-state').removeClass('active');
                } else {
                    $(this).attr('disabled', 'disabled');

                }
            });
            $('.choose-state').click(function () {
                if ($(this).is('.active')) {
                    $('.states-list').stop(true, false).slideUp();
                    $(this).removeClass('active');
                    $(this).style('height', '')
                } else {
                    $('.states-list').stop(true, false).slideDown();
                    $(this).addClass('active');
                }
            });
        });

        function selectProvince(slug) {
            var provinceName = $('.states-list li.' + slug + ' a').html();
            var path = $('.iran-map .province path[data-name=' + slug + ']');
            $('.states-list a.active').removeClass('active');
            $('.states-list li.' + slug + ' a').addClass('active');
            if (provinceName) {
                $('.choose-state h3').html(provinceName).attr('data-state', slug);
                $('.choose-state h3').removeClass('disabled');
            }
            path.parents('svg').find('.hover').removeClass('hover');
            path.addClass('hover');
            changeProvinceLink(path.attr('data-src'));
        }

        function clearSelectedProvince() {
            $('.states-list li a.active').removeClass('active');
            $('.choose-state h3').html(labels.selectState).attr('data-state', '');
            $('.choose-state h3').removeClass('disabled');
        }

        function changeProvinceLink(url) {
            var clearUrl = url.replace(/(^\w+:|^)\/\//, '');
            var linkElement = $('.state-website');
            linkElement.attr('href', url);
            linkElement.html(clearUrl);
        }

        function clearProvinceLink() {
            var linkElement = $('.state-website');
            linkElement.removeAttr('href');
            linkElement.html('');
        }
    </script>
@append