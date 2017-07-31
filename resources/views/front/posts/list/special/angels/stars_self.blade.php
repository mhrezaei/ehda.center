<?php
$circle = [
    '<circle class="circle1" fill="#fff" cx="553.5" cy="90.5" r="9"/>',
    '<circle class="circle2" fill="#fff" cx="627.5" cy="44" r="6.4"/>',
    '<circle class="circle3" fill="#fff" cx="784.1" cy="44" r="4.6"/>',
    '<circle class="circle4" fill="#fff" cx="839.2" cy="106.6" r="4.6"/>',
    '<circle class="circle5" fill="#fff" cx="856.8" cy="217.1" r="4.6"/>',
    '<circle class="circle6" fill="#fff" cx="559.1" cy="302.4" r="4.6"/>',
    '<circle class="circle7" fill="#fff" cx="455.4" cy="228.8" r="6.4"/>',
    '<circle class="circle8" fill="#fff" cx="316.6" cy="292.4" r="9.6"/>',
    '<circle class="circle9" fill="#fff" cx="249.1" cy="332.4" r="6.9"/>',
    '<circle class="circle10" fill="#fff" cx="137.6" cy="348.1" r="6.9"/>',
    '<circle class="circle11" fill="#fff" cx="17.3" cy="248.8" r="5.3"/>',
    '<circle class="circle12" fill="#fff" cx="67.6" cy="71.1" r="5.3"/>',
    '<circle class="circle13" fill="#fff" cx="143.3" cy="40.3" r="8.8"/>',
    '<circle class="circle14" fill="#fff" cx="277.3" cy="58" r="5.9"/>',
    '<circle class="circle15" fill="#fff" cx="413.8" cy="147.5" r="5.9"/>',
    '<circle class="circle16" fill="#fff" cx="417.8" cy="113.5" r="3.7"/>',
    '<circle class="circle17" fill="#fff" cx="440.6" cy="21.1" r="7.1"/>',
    '<circle class="circle18" fill="#fff" cx="442.7" cy="359.1" r="6.4"/>',
    '<circle class="circle19" fill="#fff" cx="758.7" cy="333.6" r="8.1"/>',
];
?>

<div class="row stars-bg text-center">
    <div class="stars">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
             y="0px" width="873.3px" height="379.5px" viewBox="0 0 873.3 379.5"
             style="enable-background:new 0 0 873.3 379.5" xml:space="preserve">

            @for ($i = 0; $i < count($posts); $i++)
                {!! $circle[$i] !!}
            @endfor

                </svg>
    </div>
    <div class="show-form-btn-container">
        <button type="button"><i class="fa fa-chevron-down"></i></button>
    </div>
</div>