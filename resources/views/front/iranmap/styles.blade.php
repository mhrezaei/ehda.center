<style>
    .states-list li a[disabled]::after {
        content: "({{ trans('front.this_state_is_disabled') }})";
    }

    .ehda-card {
        display: none;
    }
</style>