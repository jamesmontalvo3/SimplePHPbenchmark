<?php

$numQueries = 10;
$numIds = 20;
$queryTable = "text";
$idColumn = "old_id";

$numPhpLoops = 1000000;

// setup mysql connection
require_once __DIR__ . '/conf.php';

$db = mysql_connect(
	$conf['host'],
	$conf['user'],
	$conf['pass']
) or die ('Unable to connect. Check you connection parameters.');

mysql_select_db( $conf['db_name'], $db ) or die( mysql_error( $link ) );




$queryStart = microtime( true );

for( $i = 0; $i < $numQueries; $i++ ) {

	$ids = array();
	for( $j = 0; $j < $numIds; $j++ ) {
		$ids[] = rand( 1, 70000 );
	}
	$list = implode( ',' , $ids );

	$query = "SELECT * FROM $queryTable WHERE $idColumn IN ($list)";
	$result = mysql_query( $query, $db ) or die ( mysql_error( $db ) );

}

$queryLength = microtime( true ) - $queryStart;



$phpStart = microtime( true );

for( $k = 0; $k < $numPhpLoops; $k++ ) {

	$list = sha1( $list );

}

$phpLength = microtime( true ) - $phpStart;

$data = array(
	"database" => $queryLength,
	"php" => $phpLength,
);
header('Content-Type: application/json');
echo json_encode($data);
