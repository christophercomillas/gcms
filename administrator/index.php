<?php 
  session_start();
  include '../function.php';
  require 'header.php';

	function incrementDate($startDate, $monthIncrement = 0) {

	    $startingTimeStamp = $startDate->getTimestamp();
	    // Get the month value of the given date:
	    $monthString = date('Y-m', $startingTimeStamp);
	    // Create a date string corresponding to the 1st of the give month,
	    // making it safe for monthly calculations:
	    $safeDateString = "first day of $monthString";
	    // Increment date by given month increments:
	    $incrementedDateString = "$safeDateString $monthIncrement month";
	    $newTimeStamp = strtotime($incrementedDateString);
	    $newDate = DateTime::createFromFormat('U', $newTimeStamp);
	    return $newDate;
	}

	$currentDate = new DateTime();
	$oneMonthAgo = incrementDate($currentDate, -0);
	$twoMonthsAgo = incrementDate($currentDate, -1);
	$threeMonthsAgo = incrementDate($currentDate, -2);
	$fourMonthsAgo = incrementDate($currentDate, -3);
	$fiveMonthsAgo = incrementDate($currentDate, -4);
	$sixMonthsAgo = incrementDate($currentDate, -5);	

	function getMonth(){
		$currentDate = new DateTime();
		$oneMonthAgo = incrementDate($currentDate, -0);
	}
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-xs-12">
  		<h4>GC Sales Per Store </h4>
  		</div>
  	</div>  
   	<div class="row">
   		<div class="col-xs-3">   			
   			<div id="js-legend" class="chart-legend"></div> 
   		</div>
   		<div class="col-xs-7">
   			<canvas id="canvas" height="450px" width="600px"></canvas>			  			
   		</div>
    </div>
  </div>
<?php include 'jscripts.php'; ?>
	<script>
		var lineChartData = {
			labels : ["<?php echo $sixMonthsAgo->format('F Y'); ?>",
						"<?php echo $fiveMonthsAgo->format('F Y'); ?>",
						"<?php echo $fourMonthsAgo->format('F Y'); ?>",
						"<?php echo $threeMonthsAgo->format('F Y'); ?>",
						"<?php echo $twoMonthsAgo->format('F Y'); ?>",
						"<?php echo $oneMonthAgo->format('F Y'); ?>",
						"<?php echo $currentDate->format('F Y'); ?>"
					],
			datasets : [
				{
					label: "Alturas Mall",
					fillColor : "rgba(151,206,104,0.2)",
					strokeColor : "rgba(151,206,104,1)",
					pointColor : "rgba(151,206,104,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,206,104,1)",
					data : [
						<?php
							$x = 7;
							do 
							{
								$x--;
								echo countGCSalesByStore($link,1,$x).',';

							} while ($x != 0);
						?>
					]
				},
				{
					label: "Alturas Talibon",
					fillColor : "rgba(255,102,0,0.2)",
					strokeColor : "rgba(255,102,0,1)",
					pointColor : "rgba(255,102,0,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(255,102,0,1)",
					data : [
						<?php
							$x = 7;
							do 
							{
								$x--;
								echo countGCSalesByStore($link,2,$x).',';

							} while ($x != 0);
						?>
					]
				},
				{
					label: "Island City Bohol",
					fillColor : "rgba(0,0,154,0.2)",
					strokeColor : "rgba(0,0,154,1)",
					pointColor : "rgba(0,0,154,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(0,0,154,1)",
					data : [
						<?php
							$x = 7;
							do 
							{
								$x--;
								echo countGCSalesByStore($link,3,$x).',';

							} while ($x != 0);
						?>
					]
				},
				{
					label: "Plaza Marcela",
					fillColor : "rgba(204,0,51,0.2)",
					strokeColor : "rgba(204,0,51,1)",
					pointColor : "rgba(204,0,51,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(204,0,51,1)",
					data : [
						<?php
							$x = 7;
							do 
							{
								$x--;
								echo countGCSalesByStore($link,4,$x).',';

							} while ($x != 0);
						?>
					]
				}
			]

		}
		var options = {
		    segmentShowStroke: false,
		    animateRotate: true,
		    animateScale: false,
		    percentageInnerCutout: 50,
		    tooltipTemplate: "<%= value %>%",
		    responsive:true
		}
		var ctx = document.getElementById("canvas").getContext("2d");	
		var myChart = new Chart(ctx).Line(lineChartData, options);	
		// window.myLine = new Chart(ctx).Line(lineChartData, {
		// 	responsive: true
		// });
		document.getElementById('js-legend').innerHTML = myChart.generateLegend();

	</script>
<?php include 'footer.php' ?>
