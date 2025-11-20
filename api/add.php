<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Pour autoriser les requêtes depuis n'importe quelle origine


// Action par défaut : récupérer la valeur
$auth = isset($_GET['auth']) ? $_GET['auth']  : 'auth';
$people = isset($_GET['people']) ? $_GET['people']  : 'compteur';

if ($people == "noni" ||$people == "maxime" ||$people == "aurore" ||$people == "damnyts" ||$people == "kurai"){
    $file = '../db/'. $people .'.txt';
} else {
    $file = '../db/compteur.txt';
}

require('mdp.php');
if ($auth === $password) {
    $fp = fopen($file, 'c+');
    if (flock($fp, LOCK_EX)) { // Verrouillage exclusif
        $count = (int)fread($fp, filesize($file) ?: 1);
        $count++;
        ftruncate($fp, 0); // Effacer le fichier
        rewind($fp);       // Rembobiner le pointeur
        fwrite($fp, $count);
        fflush($fp);       // Vider le tampon
        flock($fp, LOCK_UN); // Libérer le verrou
        echo json_encode(['success' => true, 'new_count' => $count]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Could not get the lock']);
    }
    fclose($fp);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
}
?>
