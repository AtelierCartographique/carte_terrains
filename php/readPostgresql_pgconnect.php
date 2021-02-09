<?php
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
		if (empty($_GET['discipline'])) {
			$discipline = "Toutes";
		} else
			$discipline = $_GET['discipline'];
	// Fin du bloc recupération des variables avec $_Get

	// Connexion à la base de données "cours7" se trouvant sur le serveur localhost. Nous nous y connectons avec l'identifiant "postgres", et nous entrons le mot de passe que nous avons créé au moment de l'initialisation de la BD PostgreSQL
	$db = pg_connect("host=localhost dbname=terrains_dpt_geo user=postgres password=gmq717");
//	$db = pg_connect("host=localhost dbname=testdb user=rodolphe password=rodolphe");
	
	// Nous créons une requête SQL à partir des variables récupérées dans l'URL		
	if ($discipline == 'Toutes') {
		$sql = 'SELECT gid, titre, discipline, authors, ST_AsGeoJSON(geom, 4) as geom FROM "terrains2"';
	} else {
		$sql = 'SELECT gid, titre, discipline, authors, ST_AsGeoJSON(geom, 4) as geom FROM "terrains2" WHERE discipline like \'%' . $discipline . '%\'';
	}
	$query = pg_exec($db, $sql);
	
	$features = [];
	for ($i = 0; $i < pg_numrows($query); $i++) {
		$row = pg_fetch_assoc($query,$i);
		$geometry = $row['geom'] = json_decode($row['geom']);
		unset($row['geom']);
		// Nous retirons les résultats redondants
		unset($row[0]);
		unset($row[1]);
		unset($row[2]);
		unset($row[3]);
		unset($row[4]);
		// Nous reconstituons notre 'feature' sur le modèle GeoJSON
		$feature = ['type' => 'Feature', 'geometry' => $geometry, 'properties' => $row];
		array_push($features, $feature);
	};
	// Nous imbriquons nos entités dans une collection d'entités GeoJSON
	$featureCollection = ["type" => "FeatureCollection", "features" => $features];
	echo stripslashes(json_encode($featureCollection));
	pg_close($db);
?>