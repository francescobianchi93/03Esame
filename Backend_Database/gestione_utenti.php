<?php
session_start();
require_once 'db_config.php';


// Controlla se l'utente è loggato e se è un amministratore
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Configurazione della connessione al database (utilizza le tue credenziali reali)
$pdo = new PDO('mysql:host=localhost;dbname=login', 'root', ''); // sostituisci con le tue credenziali

// Funzione per la validazione e la sanificazione dell'input
function validate_and_sanitize($input) {
    // Implementa la tua logica di validazione e sanificazione qui
    return $input;
}

// Aggiungi un nuovo utente
if (isset($_POST['azione']) && $_POST['azione'] === 'Aggiungi') {
    $username = validate_and_sanitize($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = validate_and_sanitize($_POST['email']);

    // Inserimento nel database
    $stmt = $pdo->prepare("INSERT INTO utenti (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $email]);
}

// Elimina un utente
if (isset($_POST['azione']) && $_POST['azione'] === 'Elimina') {
    $id = validate_and_sanitize($_POST['id']);
    
    // Eliminazione dal database
    $stmt = $pdo->prepare("DELETE FROM utenti WHERE id = ?");
    $stmt->execute([$id]);
}

// Modifica un utente esistente
if (isset($_POST['azione']) && $_POST['azione'] === 'Modifica') {
    $id = validate_and_sanitize($_POST['id']);
    $username = validate_and_sanitize($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = validate_and_sanitize($_POST['email']);

    // Aggiornamento nel database
    $stmt = $pdo->prepare("UPDATE utenti SET username = ?, password = ?, email = ? WHERE id = ?");
    $stmt->execute([$username, $password, $email, $id]);
}

// Ottieni tutti gli utenti
$stmt = $pdo->prepare("SELECT * FROM utenti");
$stmt->execute();
$utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Utenti</title>
    <link rel="stylesheet" href="./css_backend/style_utenti.css">    
</head>
<body>
    <h1>Gestione Utenti</h1>

    <h2>Aggiungi Utente</h2>
    <!-- Form per l'aggiunta di un nuovo utente -->
    <form method="post" action="gestione_utenti.php">
        <input type="text" name="username" required placeholder="Username">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <input type="hidden" name="azione" value="Aggiungi">
        <input type="submit" value="Aggiungi">
    </form>

    <h2>Utenti Esistenti</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utenti as $utente): ?>
            <tr>
                <td><?php echo htmlspecialchars($utente['id']); ?></td>
                <td><?php echo htmlspecialchars($utente['username']); ?></td>
                <td><?php echo htmlspecialchars($utente['email']); ?></td>
                <td>
                    <!-- Form per la modifica di un utente esistente -->
                    <form method="post" action="gestione_utenti.php">
                        <input type="hidden" name="id" value="<?php echo $utente['id']; ?>">
                        <input type="text" name="username" required value="<?php echo $utente['username']; ?>">
                        <input type="email" name="email" required value="<?php echo $utente['email']; ?>">
                        <input type="password" name="password" placeholder="Nuova password">
                        <input type="hidden" name="azione" value="Modifica">
                        <input type="submit" value="Modifica">
                    </form>
                    <!-- Form per l'eliminazione di un utente -->
                    <form method="post" action="gestione_utenti.php">
                        <input type="hidden" name="id" value="<?php echo $utente['id']; ?>">
                        <input type="hidden" name="azione" value="Elimina">
                        <input type="submit" value="Elimina">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
