<?php
	
	if (empty($_GET['lat'])) {
		$lat = 0;
	} else
		$lat = $_GET['lat'];
	
	if (empty($_GET['lng'])) {
		$lng = 0;
	} else
		$lng = $_GET['lng'];
	
	if (empty($_GET['type'])) {
		$type = 0;
	} else
		$type = $_GET['type'];
	
	if (empty($_GET['authors'])) {
		$authors = 0;
	} else
		$authors = $_GET['authors'];
	
	if (empty($_GET['id'])) {
		$id = 0;
	} else
		$id = $_GET['id'];
	
	if (empty($_GET['erase'])) {
		$erase = 'false';
	} else
		$erase = $_GET['erase'];

//	$db = new PDO("pgsql:host=localhost; dbname=terrains_dpt_geo", "postgres", "gmq717");
	$db = pg_connect("host=localhost dbname=terrains_dpt_geo user=postgres password=gmq717");
//	$db = pg_connect("host=localhost dbname=testdb user=rodolphe password=rodolphe");
	
//	$sql = 'SELECT titre, discipline, ST_AsGeoJSON(geom, 5) as geom FROM "terrains"';
	if ($erase == 'true') {
		$sql = 'DELETE FROM "terrains2" WHERE gid = ' . $id;
	} else  {
		$sql = "INSERT INTO terrains2(geom,titre,title,context,discipline,authors) VALUES(ST_GeomFromText('POINT(" . $lng . " " . $lat . ")', 4326),'Un titre', 'A title', 'Doctorat'"
		. ",'" . $type
		. "'," . $authors
		. ")";
	}
	echo $sql;
	$query = pg_exec($db, $sql);
	pg_close($db);
?>