<?php
session_start();
require_once 'db_config.php'; // Sostituisci con il percorso corretto al tuo file di configurazione del database

// Verifica se l'utente è amministratore
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

function validate_and_sanitize($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

// Connessione al database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password, $options);
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

// Operazioni CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $azione = validate_and_sanitize($_POST['azione'] ?? '');

    if ($azione === 'Aggiungi') {
        $titolo_sezione = validate_and_sanitize($_POST['titolo_sezione'] ?? '');
        $video_src = validate_and_sanitize($_POST['video_src'] ?? '');
        $video_fallback_text = validate_and_sanitize($_POST['video_fallback_text'] ?? '');
        $bottone_testo = validate_and_sanitize($_POST['bottone_testo'] ?? '');
        $bottone_link = validate_and_sanitize($_POST['bottone_link'] ?? '');

        $stmt = $pdo->prepare("INSERT INTO contenuto (titolo_sezione, video_src, video_fallback_text, bottone_testo, bottone_link) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$titolo_sezione, $video_src, $video_fallback_text, $bottone_testo, $bottone_link]);
    } elseif ($azione === 'Modifica') {
        $id = validate_and_sanitize($_POST['id']);
        $titolo_sezione = validate_and_sanitize($_POST['titolo_sezione'] ?? '');
        $video_src = validate_and_sanitize($_POST['video_src'] ?? '');
        $video_fallback_text = validate_and_sanitize($_POST['video_fallback_text'] ?? '');
        $bottone_testo = validate_and_sanitize($_POST['bottone_testo'] ?? '');
        $bottone_link = validate_and_sanitize($_POST['bottone_link'] ?? '');

        $stmt = $pdo->prepare("UPDATE contenuto SET titolo_sezione = ?, video_src = ?, video_fallback_text = ?, bottone_testo = ?, bottone_link = ? WHERE id = ?");
        $stmt->execute([$titolo_sezione, $video_src, $video_fallback_text, $bottone_testo, $bottone_link, $id]);
    } elseif ($azione === 'Elimina') {
        $id = validate_and_sanitize($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM contenuto WHERE id = ?");
        $stmt->execute([$id]);
    }
}

$stmt = $pdo->prepare("SELECT * FROM contenuto");
$stmt->execute();
$caratteristiche = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Caratteristiche</title>
    <link rel="stylesheet" href="./css_backend/style_caratteristiche.css">
</head>
<body>
    <h1>Gestione Caratteristiche</h1>

    <form method="post" action="">
        <input type="text" name="titolo_sezione" required placeholder="Titolo della Sezione">
        <input type="text" name="video_src" placeholder="Percorso del Video">
        <input type="text" name="video_fallback_text" placeholder="Testo di fallback del Video">
        <input type="text" name="bottone_testo" placeholder="Testo del Bottone">
        <input type="text" name="bottone_link" placeholder="Link del Bottone">
        <input type="hidden" name="azione" value="Aggiungi">
        <input type="submit" value="Aggiungi Caratteristica">
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titolo Sezione</th>
                <th>Video Src</th>
                <th>Video Fallback Text</th>
                <th>Bottone Testo</th>
                <th>Bottone Link</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($caratteristiche as $caratteristica): ?>
            <tr>
                <td><?php echo htmlspecialchars($caratteristica['id']); ?></td>
                <td><?php echo htmlspecialchars($caratteristica['titolo_sezione']); ?></td>
                <td><?php echo htmlspecialchars($caratteristica['video_src']); ?></td>
                <td><?php echo htmlspecialchars($caratteristica['video_fallback_text']); ?></td>
                <td><?php echo htmlspecialchars($caratteristica['bottone_testo']); ?></td>
                <td><?php echo htmlspecialchars($caratteristica['bottone_link']); ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($caratteristica['id']); ?>">
                        <input type="text" name="titolo_sezione" required value="<?php echo htmlspecialchars($caratteristica['titolo_sezione']); ?>">
                        <input type="text" name="video_src" value="<?php echo htmlspecialchars($caratteristica['video_src']); ?>">
                        <input type="text" name="video_fallback_text" value="<?php echo htmlspecialchars($caratteristica['video_fallback_text']); ?>">
                        <input type="text" name="bottone_testo" value="<?php echo htmlspecialchars($caratteristica['bottone_testo']); ?>">
                        <input type="text" name="bottone_link" value="<?php echo htmlspecialchars($caratteristica['bottone_link']); ?>">
                        <input type="hidden" name="azione" value="Modifica">
                        <input type="submit" value="Modifica Caratteristica">
                    </form>
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($caratteristica['id']); ?>">
                        <input type="hidden" name="azione" value="Elimina">
                        <input type="submit" value="Elimina Caratteristica">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
