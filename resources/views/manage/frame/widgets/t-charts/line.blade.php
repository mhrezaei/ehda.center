<?php
if(!isset($id)) {
	$id = "chartLine" . rand(1000, 9999);
}
?>

<canvas id="{{$id}}" height="{{$height or 100}}" style="max-width: 100%;width: 100%;"></canvas>
<script>
	Chart.defaults.global.defaultFontFamily = 'IRANSans, Tahoma';
	var labels = [<?php echo '"' . implode('","', array_keys($data)) . '"' ?>];
	var data = [<?php echo '"' . implode('","', array_values($data)) . '"' ?>];
	forms_log(data);
	var ctx = $("#{{$id}}");
	var myChart = new Chart(ctx, {
		type   : 'line',
		font   : 'tahoma',
		data   : {
			labels  : labels,
			datasets: [
				{
					label          : '{{ $label or false }}',
					data           : data,
					backgroundColor: [
						'rgba(75, 192, 192, 0.2)',

						'rgba(153, 102, 255, 0.2)',
						'rgba(255, 99, 132, 0.2)',
						'rgba(54, 162, 235, 0.2)',
						'rgba(255, 206, 86, 0.2)',
						'rgba(255, 159, 64, 0.2)'
					],
					borderColor    : [
						'rgba(75, 192, 192, 1)',
						'rgba(255,99,132,1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(153, 102, 255, 1)',
						'rgba(255, 159, 64, 1)'
					],
					borderWidth    : 1
				}
			],
		},
		options: {
			legend  : {
				display: true
			},
			tooltips: {
				enabled: false
			},
		}
	});
</script>
