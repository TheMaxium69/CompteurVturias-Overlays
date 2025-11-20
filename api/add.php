<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Pour autoriser les requêtes depuis n'importe quelle origine

$file = 'compteur.txt';

// Action par défaut : récupérer la valeur
$action = isset($_GET['action']) ? $_GET['action']  : 'get';

if ($action == 'increment') {
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
} elseif ($action == 'get') {
    if (!file_exists($file)) {
        file_put_contents($file, '0');
    }
    $count = (int)file_get_contents($file);
    echo json_encode(['count' => $count]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
}
?>
