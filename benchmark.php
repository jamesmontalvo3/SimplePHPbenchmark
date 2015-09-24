<?php

// get time in milliseconds of script start
$scriptStart = microtime( true );

// benchmark configuration
$numQueries = $_GET['num_queries'];
$numIds = $_GET['num_ids'];
$queryTable = "text";
$idColumn = "old_id";
$numPhpLoops = $_GET['php_loops'];

// setup mysql connection
require_once __DIR__ . '/conf.php';
$db = mysql_connect(
	$conf['host'],
	$conf['user'],
	$conf['pass']
) or die ('Unable to connect. Check you connection parameters.');
mysql_select_db( $conf['db_name'], $db ) or die( mysql_error( $link ) );

// get time of query start
$queryStart = microtime( true );

// do a bunch of loops, repeating similar queries (but randomly different
// so caching shouldn't take effect)
for( $i = 0; $i < $numQueries; $i++ ) {

	$ids = array();
	for( $j = 0; $j < $numIds; $j++ ) {
		$ids[] = rand( 1, 70000 );
	}
	$list = implode( ',' , $ids );

	$query = "SELECT * FROM $queryTable WHERE $idColumn IN ($list)";
	$result = mysql_query( $query, $db ) or die ( mysql_error( $db ) );

}

// record length of database test
$queryLength = microtime( true ) - $queryStart;

// record start of php test
$phpStart = microtime( true );

// do a bunch of loops (thousands or millions) doing a fairly complex task
// of generating a cryptographic hash.
for( $k = 0; $k < $numPhpLoops; $k++ ) {
	$list = sha1( $list );
}

// record php test length
$phpLength = microtime( true ) - $phpStart;

// respond with data
header('Content-Type: application/json');
$data = array(
	"script" => round( microtime( true ) - $scriptStart, 3),
	"database" => round( $queryLength, 3),
	"php" => round( $phpLength, 3),
);
echo json_encode($data);
