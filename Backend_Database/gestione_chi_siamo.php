<?php
session_start();
require_once 'db_config.php';

// Controlla se l'utente è loggato come amministratore, altrimenti reindirizza alla pagina di login
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Funzioni di validazione e sanificazione
function validate_and_sanitize($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Gestione degli errori
function handle_error($message) {
    echo "<p>Error: $message</p>";
}

// Connessione al database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password, $options);
} catch (PDOException $e) {
    handle_error($e->getMessage());
    exit;
}

// Ottenere i dati da informazioni_sito
$stmt = $pdo->prepare("SELECT * FROM informazioni_sito");
$stmt->execute();
$informazioni_sito = $stmt->fetch(PDO::FETCH_ASSOC);

// Ottenere i dati da squadra
$stmt = $pdo->prepare("SELECT * FROM squadra");
$stmt->execute();
$squadra = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Modifica dei dati in informazioni_sito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifica_chi_siamo'])) {
    $chi_siamo_titolo = validate_and_sanitize($_POST['chi_siamo_titolo']);
    $chi_siamo_testo = validate_and_sanitize($_POST['chi_siamo_testo']);
    $vision_titolo = validate_and_sanitize($_POST['vision_titolo']);
    $vision_testo = validate_and_sanitize($_POST['vision_testo']);

    $stmt = $pdo->prepare("UPDATE informazioni_sito SET chi_siamo_titolo = ?, chi_siamo_testo = ?, vision_titolo = ?, vision_testo = ? WHERE id = ?");
    $stmt->execute([$chi_siamo_titolo, $chi_siamo_testo, $vision_titolo, $vision_testo, $informazioni_sito['id']]);

    // Aggiorna la variabile $informazioni_sito con i nuovi dati
    $informazioni_sito['chi_siamo_titolo'] = $chi_siamo_titolo;
    $informazioni_sito['chi_siamo_testo'] = $chi_siamo_testo;
    $informazioni_sito['vision_titolo'] = $vision_titolo;
    $informazioni_sito['vision_testo'] = $vision_testo;
}

// Aggiungi un nuovo membro alla squadra
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aggiungi_membro'])) {
    $nome = validate_and_sanitize($_POST['nome_membro']);
    $immagine = validate_and_sanitize($_POST['immagine_membro']);
    $descrizione = validate_and_sanitize($_POST['descrizione_membro']);

    $stmt = $pdo->prepare("INSERT INTO squadra (nome, immagine, descrizione, informazioni_sito_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $immagine, $descrizione, $informazioni_sito['id']]);
}

// Rimuovi un membro dalla squadra
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rimuovi_membro'])) {
    $membro_id = validate_and_sanitize($_POST['membro_id']);
    $stmt = $pdo->prepare("DELETE FROM squadra WHERE id = ?");
    $stmt->execute([$membro_id]);
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Chi Siamo</title>
    <link rel="stylesheet" type="text/css" href="./css_backend/style_chi_siamo.css">
</head>
<body>
    <h1>Gestione Chi Siamo</h1>

    <!-- Modifica dati di informazioni_sito -->
    <h2>Modifica Informazioni Sito</h2>
    <form method="post" action="">
        <label for="chi_siamo_titolo">Titolo Chi Siamo:</label>
        <input type="text" name="chi_siamo_titolo" value="<?php echo htmlspecialchars($informazioni_sito['chi_siamo_titolo']); ?>" required><br>
        <label for="chi_siamo_testo">Testo Chi Siamo:</label>
        <textarea name="chi_siamo_testo" required><?php echo htmlspecialchars($informazioni_sito['chi_siamo_testo']); ?></textarea><br>
        <label for="vision_titolo">Titolo Vision:</label>
        <input type="text" name="vision_titolo" value="<?php echo htmlspecialchars($informazioni_sito['vision_titolo']); ?>" required><br>
        <label for="vision_testo">Testo Vision:</label>
        <textarea name="vision_testo" required><?php echo htmlspecialchars($informazioni_sito['vision_testo']); ?></textarea><br>
        <input type="submit" name="modifica_chi_siamo" value="Salva Modifiche">
    </form>

    <!-- Aggiungi membro alla squadra -->
    <h2>Aggiungi Membro alla Squadra</h2>
    <form method="post" action="">
        <label for="nome_membro">Nome:</label>
        <input type="text" name="nome_membro" required><br>
        <label for="immagine_membro">Immagine:</label>
        <input type="text" name="immagine_membro" required><br>
        <label for="descrizione_membro">Descrizione:</label>
        <textarea name="descrizione_membro" required></textarea><br>
        <input type="submit" name="aggiungi_membro" value="Aggiungi Membro">
    </form>

    <!-- Visualizza membri della squadra -->
    <h2>Squadra</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Immagine</th>
                <th>Descrizione</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($squadra as $membro): ?>
            <tr>
                <td><?php echo htmlspecialchars($membro['nome']); ?></td>
                <td><?php echo htmlspecialchars($membro['immagine']); ?></td>
                <td><?php echo htmlspecialchars($membro['descrizione']); ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="membro_id" value="<?php echo $membro['id']; ?>">
                        <input type="submit" name="rimuovi_membro" value="Rimuovi">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
