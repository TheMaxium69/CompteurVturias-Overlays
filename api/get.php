<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Pour autoriser les requêtes depuis n'importe quelle origine

$people = isset($_GET['people']) ? $_GET['people']  : 'compteur';

if ($people == "noni" ||$people == "maxime" ||$people == "aurore" ||$people == "damnyts" ||$people == "kurai"){
    $file = '../db/'. $people .'.txt';
} else if ($people != "all") {
    $file = '../db/compteur.txt';
}

if ($people != "all") {
    if (!file_exists($file)) {
        file_put_contents($file, '0');
    }
    $count = (int)file_get_contents($file);
    echo json_encode(['count' => $count]);
} else {
    $counters = array();
    $files = array('noni', 'maxime', 'aurore', 'damnyts', 'kurai');

    foreach ($files as $counter_name) {
        $counter_file = '../db/' . $counter_name . '.txt';
        if (!file_exists($counter_file)) {
            file_put_contents($counter_file, '0');
        }
        $counters[$counter_name] = (int)file_get_contents($counter_file);
    }

    echo json_encode($counters);
}

?>
