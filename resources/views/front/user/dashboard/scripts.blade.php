<script>
    $(document).ready(function () {
        // Defining popover
        $('.has-popover-tab').popover({
            trigger: 'manual',
            placement: 'top',
            animate: true,
            delay: 500,
            container: 'body'
        }).first().popover('show');

        // On change tab
        $(document).on('shown.bs.tab', '.has-popover-tab', function (e) {
            var tabBtn = $(e.target);
            var cardType = $(this).data('cardType');

            // Showing popover for selected tab
            if (!$('#' + tabBtn.attr('aria-describedby') + '.popover:visible').length) {
                // popover is visible
                tabBtn.popover('show');
            }

            // Hiding popover for other tabs
            tabBtn.closest('li')
                .siblings('li')
                .children('.has-popover-tab')
                .popover('hide');

            // Changing "href" attribute of ".download-btn"
            var url = downloadLinks[cardType];
            $('.download-btn').attr('href', url);

            if (cardType == 'social') {
                openSharing();
            } else {
                closeSharing();
            }
        });

        $('.share-btn').click(function (e) {
            e.preventDefault();
            if (!$('.share').is(':visible')) {
                $('a.has-popover-tab[data-card-type=social]').tab('show')
            }
        });

        $('.print-btn').click(function (e) {
            $('a.has-popover-tab[data-card-type=full]').tab('show')
        });

    });

    var downloadLinks = {!! json_encode($downloadLinks) !!};

    function openSharing() {
        $('.share').slideDown()
    }

    function closeSharing() {
        $('.share').slideUp()
    }
</script>