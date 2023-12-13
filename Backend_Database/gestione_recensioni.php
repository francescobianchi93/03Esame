<?php
session_start();
require_once 'db_config.php';

// Verifica se l'utente è amministratore
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

// Aggiungi una nuova recensione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['azione'])) {
    $nome = isset($_POST['nome']) ? validate_and_sanitize($_POST['nome']) : null;
    $tipo = isset($_POST['tipo']) ? validate_and_sanitize($_POST['tipo']) : null;
    $testo = isset($_POST['testo']) ? validate_and_sanitize($_POST['testo']) : null;
    $src = isset($_POST['src']) ? validate_and_sanitize($_POST['src']) : null;

    if ($_POST['azione'] === 'Aggiungi') {
        $stmt = $pdo->prepare("INSERT INTO recensioni (nome, tipo, testo, src) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $tipo, $testo, $src]);
    } elseif ($_POST['azione'] === 'Modifica') {
        $id = validate_and_sanitize($_POST['id']);
        $stmt = $pdo->prepare("UPDATE recensioni SET nome = ?, tipo = ?, testo = ?, src = ? WHERE id = ?");
        $stmt->execute([$nome, $tipo, $testo, $src, $id]);
    } elseif ($_POST['azione'] === 'Elimina') {
        $id = validate_and_sanitize($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM recensioni WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Ottieni tutte le recensioni
$stmt = $pdo->prepare("SELECT * FROM recensioni");
$stmt->execute();
$recensioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Recensioni</title>
    <link rel="stylesheet" type="text/css" href="./css_backend/style_recensioni.css">
</head>
<body>
    <h1>Gestione Recensioni</h1>

    <!-- Form per l'aggiunta di una nuova recensione -->
    <form method="post" action="">
        <input type="text" name="nome" requir ed placeholder="Nome">
        <select name="tipo" required>
            <option value="video">Video</option>
            <option value="testo">Testo</option>
        </select>
        <textarea name="testo" placeholder="Testo della recensione"></textarea>
        <input type="text" name="src" required placeholder="Percorso video o immagine">
        <input type="hidden" name="azione" value="Aggiungi">
        <input type="submit" value="Aggiungi Recensione">
    </form>

 

    <!-- Tabella per visualizzare le recensioni esistenti -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Testo</th>
                <th>Video/Immagine</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recensioni as $recensione): ?>
            <tr>
                <td><?php echo htmlspecialchars($recensione['id']); ?></td>
                <td><?php echo htmlspecialchars($recensione['nome']); ?></td>
                <td><?php echo htmlspecialchars($recensione['tipo']); ?></td>
                <td><?php echo htmlspecialchars($recensione['testo']); ?></td>
                <td><?php echo htmlspecialchars($recensione['src']); ?></td>
                <td>
                       <!-- Form per la modifica di una recensione esistente -->
<form method="post" action="">
    <input type="hidden" name="id" value="<?php echo $recensione['id']; ?>">
    <input type="text" name="nome" required value="<?php echo $recensione['nome']; ?>">
    <select name="tipo" required>
        <option value="video" <?php echo $recensione['tipo'] == 'video' ? 'selected' : ''; ?>>Video</option>
        <option value="testo" <?php echo $recensione['tipo'] == 'testo' ? 'selected' : ''; ?>>Testo</option>
    </select>
    <textarea name="testo"><?php echo $recensione['testo']; ?></textarea>
    <input type="text" name="src" required value="<?php echo $recensione['src']; ?>">
    <input type="hidden" name="azione" value="Modifica">
    <input type="submit" value="Modifica Recensione">
</form>

                    </form>
                    <!-- Form per l'eliminazione di una recensione -->
<form method="post" action="">
    <input type="hidden" name="id" value="<?php echo $recensione['id']; ?>">
    <input type="hidden" name="azione" value="Elimina">
    <input type="submit" value="Elimina Recensione" onclick="return confirm('Sei sicuro di voler eliminare questa recensione?');">
</form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
