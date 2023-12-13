<?php
// File JSON con i dati da importare
$jsonFilePath = '../Dati Form/dati_utenti.json';

try {
    // Connessione al database
    require_once 'db_config.php';
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password, $options);

    // Leggi il file JSON
    $json = file_get_contents($jsonFilePath);

    // Decodifica il file JSON in un array associativo
    $data = json_decode($json, true);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Errore nella decodifica del JSON.");
    }

    // Prepara la query di inserimento
    $stmt = $pdo->prepare("INSERT INTO form_utenti (nome, cognome, email, telefono, messaggio) VALUES (?, ?, ?, ?, ?)");

    foreach ($data as $record) {
        // Esegui l'inserimento dei dati per ciascun record
        $stmt->execute([
            $record['nome'],
            $record['cognome'],
            $record['email'],
            $record['telefono'],
            $record['messaggio']
        ]);
    }

    echo "Dati importati con successo.";

} catch (PDOException $e) {
    echo "Errore di connessione al database: " . $e->getMessage();
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}
?>
