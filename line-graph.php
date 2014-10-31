<html>
  <head>
    <script type="text/javascript" src="line_graph.js"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() 
		{
			var data = google.visualization.arrayToDataTable([
			  ['Year', 'Quiz', 'Class', 'Skill'],
			  ['Jan',  1000, 400, 900],
			  ['Feb',  1170, 500, 800],
			  ['Mar',  '',   550, 700],
			  ['Apr',  '',   600, 1100],
			  ['May',  660,  700, 110],
			  ['June', 660,  800, 550],
			  ['July', 660,  900, 630],
			  ['Aug',  760,  950, 200],
			  ['Sep',  660,  980, 111],
			  ['Oct',  960,  700, 666],
			  ['Nov',  660,  800, 987],
			  ['Dec',  460,  900, 420],
			  /*['Year', 'Sales', 'Expenses'],
			  ['2004',  1000,      400],
			  ['2004',  1170,      460],
			  ['2004',  660,       1120],
			  ['2004',  1030,      540]*/
			]);

			var options = {
			  //title: 'Company Performance'
			};

			var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

			chart.draw(data, options);
		}
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>