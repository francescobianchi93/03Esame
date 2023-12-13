<?php
session_start();
require_once 'db_config.php';

// Verifica se l'utente è amministratore
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Funzione di validazione e sanificazione
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

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password, $options);
} catch (PDOException $e) {
    handle_error($e->getMessage());
    exit;
}

// Aggiungi un nuovo record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['azione'])) {
    $nome = isset($_POST['nome']) ? validate_and_sanitize($_POST['nome']) : null;
    $cognome = isset($_POST['cognome']) ? validate_and_sanitize($_POST['cognome']) : null;
    $email = isset($_POST['email']) ? validate_and_sanitize($_POST['email']) : null;
    $telefono = isset($_POST['telefono']) ? validate_and_sanitize($_POST['telefono']) : null;
    $messaggio = isset($_POST['messaggio']) ? validate_and_sanitize($_POST['messaggio']) : null;

    if ($_POST['azione'] === 'Aggiungi') {
        $stmt = $pdo->prepare("INSERT INTO form_utenti (nome, cognome, email, telefono, messaggio) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $cognome, $email, $telefono, $messaggio]);
    } elseif ($_POST['azione'] === 'Modifica') {
        $id = validate_and_sanitize($_POST['id']);
        $stmt = $pdo->prepare("UPDATE form_utenti SET nome = ?, cognome = ?, email = ?, telefono = ?, messaggio = ? WHERE id = ?");
        $stmt->execute([$nome, $cognome, $email, $telefono, $messaggio, $id]);
    } elseif ($_POST['azione'] === 'Elimina') {
        $id = validate_and_sanitize($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM form_utenti WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Ottieni tutti i record
$stmt = $pdo->prepare("SELECT * FROM form_utenti");
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Form Utenti</title>
    <link rel="stylesheet" type="text/css" href="./css_backend/style_form.css">
</head>
<body>
    <h1>Gestione Form Utenti</h1>

    <!-- Form per l'aggiunta di un nuovo record -->
    <form method="post" action="">
        <input type="text" name="nome" required placeholder="Nome">
        <input type="text" name="cognome" required placeholder="Cognome">
        <input type="email" name="email" required placeholder="Email">
        <input type="text" name="telefono" placeholder="Telefono">
        <textarea name="messaggio" placeholder="Messaggio"></textarea>
        <input type="hidden" name="azione" value="Aggiungi">
        <input type="submit" value="Aggiungi Record">
    </form>

    <!-- Tabella per visualizzare i record esistenti -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Messaggio</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $record): ?>
            <tr>
                <td><?php echo htmlspecialchars($record['id']); ?></td>
                <td><?php echo htmlspecialchars($record['nome']); ?></td>
                <td><?php echo htmlspecialchars($record['cognome']); ?></td>
                <td><?php echo htmlspecialchars($record['email']); ?></td>
                <td><?php echo htmlspecialchars($record['telefono']); ?></td>
                <td><?php echo htmlspecialchars($record['messaggio']); ?></td>
                <td>
                    <!-- Form per la modifica di un record esistente -->
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
                        <input type="text" name="nome" required value="<?php echo $record['nome']; ?>">
                        <input type="text" name="cognome" required value="<?php echo $record['cognome']; ?>">
                        <input type="email" name="email" required value="<?php echo $record['email']; ?>">
                        <input type="text" name="telefono" value="<?php echo $record['telefono']; ?>">
                        <textarea name="messaggio"><?php echo $record['messaggio']; ?></textarea>
                        <input type="hidden" name="azione" value="Modifica">
                        <input type="submit" value="Modifica Record">
                    </form>

                    <!-- Form per l'eliminazione di un record -->
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
                        <input type="hidden" name="azione" value="Elimina">
                        <input type="submit" value="Elimina Record">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
