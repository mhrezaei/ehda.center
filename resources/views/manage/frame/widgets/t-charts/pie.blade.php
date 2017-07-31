<?php
if(!isset($id)) {
	$id = "chartPie" . rand(1000, 9999);
}

?>

<canvas id="{{$id}}" height="{{$height or 100}}" style="max-width: 100%;width: 100%;"></canvas>
<script>
	var labels = [<?php echo '"' . implode('","', array_keys($data)) . '"' ?>];
	var data = [<?php echo '"' . implode('","', array_values($data)) . '"' ?>];


	forms_log(data);
	var ctx = $("#{{$id}}");
	var myChart = new Chart(ctx, {
		type   : 'pie',
		data   : {
			labels  : labels,
			datasets: [
				{
					label          : '{{ $label or '' }}',
					data           : data,
					backgroundColor: [
						'rgba(255, 99, 132, 0.2)',
						'rgba(54, 162, 235, 0.2)',
						'rgba(255, 206, 86, 0.2)',
						'rgba(75, 192, 192, 0.2)',
						'rgba(153, 102, 255, 0.2)',
						'rgba(255, 159, 64, 0.2)'
					],
					borderColor    : [
						'rgba(255,99,132,1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(75, 192, 192, 1)',
						'rgba(153, 102, 255, 1)',
						'rgba(255, 159, 64, 1)'
					],
					borderWidth    : 1
				}
			],
		},
		options: {
			legend    : {
				display: true
			},
			tooltips  : {
				enabled: false
			},
			pieceLabel: {
				// mode 'label', 'value' or 'percentage', default is 'percentage'
				mode: '{{ $piece_label or  'percentage'}}' ,

				// precision for percentage, default is 0
				precision: 1,

				//identifies whether or not labels of value 0 are displayed, default is false
				showZero: true,

				// font size, default is defaultFontSize
				fontSize: {{ $label_size or 12 }},

				// font color, default is '#fff'
				fontColor: '#424754',

				// font style, default is defaultFontStyle
				fontStyle: 'normal',

				// font family, default is defaultFontFamily
				fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

				// draw label in arc, default is false
				arc: false,

				// position to draw label, available value is 'default', 'border' and 'outside'
				// default is 'default'
				position: 'default',

				// format text, work when mode is 'value'
				format: function (value) {
					return '$' + value;
				}
			}
		}
	});
</script>
