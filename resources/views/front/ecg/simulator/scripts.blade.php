@if($dev)
    {{ Html::script('assets/libs/ecg/simulator-constants-dev.js') }}
    {{ Html::script('assets/libs/ecg/simulator.js') }}
@else
    {{ Html::script('assets/libs/ecg/simulator-constants.min.js') }}
    {{ Html::script('assets/libs/ecg/simulator.min.js') }}
@endif