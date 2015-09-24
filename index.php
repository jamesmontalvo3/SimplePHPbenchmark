<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>SimplePHPbenchmark</title>

    <!-- Bootstrap -->
    <!-- <link rel="stylesheet" href="bootstrap-3.3.5/css/bootstrap.min.css"> -->

    <!-- Optional theme -->
    <!-- <link rel="stylesheet" href="bootstrap-3.3.5/css/bootstrap-theme.min.css"> -->

    <!-- Application CSS -->
    <!-- <link rel="stylesheet" href="main.css"> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

  	<h1>Simple PHP Benchmark</h1>

  	<table>
  		<thead>
  			<tr>
  				<th>Timestamp</th><th>Request</th><th>Script</th><th>Database</th><th>PHP-intense</th>
  			</tr>
  		</thead>
  		<tbody id="table-body">

  		</tbody>
  	</table>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript">

    	var queries = 100,
    		ids = 1000,
    		loops = 5000000,
    		period = 15000;

    	window.tabularData = '';

		function getBenchmark () {

			window.requestStart = (new Date).getTime();
			var start = new Date();
			window.timestamp =
				('0' + start.getUTCHours()).slice(-2) + ":" +
				('0' + start.getUTCMinutes()).slice(-2) + ":" +
				('0' + start.getUTCSeconds()).slice(-2);
			console.log( "sending request..." );
			$.getJSON(
				"benchmark.php",
				{
					'num_queries' : queries,
					'num_ids' : ids,
					'php_loops' : loops
				},
				function ( response ) {
					var d = new Date();
					var requestLength = ( d.getTime() - window.requestStart ) / 1000;
					window.requestStart = 0;

					tabularData += window.timestamp + "\t" +
						requestLength + "\t" +
						response.script + "\t" +
						response.database + "\t" +
						response.php + "\n";

					$("#table-body").append(
						"<tr><td>"+window.timestamp+"</td><td>"+requestLength+"</td><td>"+response.script+"</td><td>"+response.database+"</td><td>"+response.php+"</td></tr>"
					);

				}
			);

			// repeat this function every X milliseconds
			setTimeout( getBenchmark, period );

		}

		getBenchmark();

    </script>
  </body>
</html>