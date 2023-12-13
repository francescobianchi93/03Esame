<?php
session_start();

// Configurazione della connessione al database
$host = 'localhost'; // o il tuo host specifico
$db = 'login'; // nome del tuo database
$user = 'root'; // il tuo username per il database
$password = ''; // la tua password per il database

// Verifica se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connessione al database
    try { 
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparazione e esecuzione della query
        $stmt = $pdo->prepare("SELECT * FROM utenti WHERE username = :username");
        $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
        $stmt->execute();

        // Verifica dell'utente e della password
        $utente = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($utente && password_verify($_POST['password'], $utente['password'])) {
            // Impostazione delle variabili di sessione
            $_SESSION['user_id'] = $utente['id'];
            $_SESSION['username'] = $utente['username'];

            // Impostazione del ruolo amministratore nella sessione
    $_SESSION['is_admin'] = ($utente['ruolo'] === 'amministratore');

            // Reindirizzamento all'area amministrativa
            header("Location: dashboard.php");
            exit;
        } else {
            $erroreLogin = "Username o password non validi.";
        }
    } catch (PDOException $e) {
        $erroreLogin = "Errore di connessione al database: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        /* Aggiungi qui il tuo CSS o includi un file CSS esterno */
        body { font-family: Arial, sans-serif; }
        .login-container { max-width: 300px; margin: auto; padding-top: 50px; }
        label { display: block; }
        .form-group { margin-bottom: 15px; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Amministratore</h2>
        <?php if (!empty($erroreLogin)): ?>
            <p class="error"><?php echo $erroreLogin; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Login">
            </div>
        </form>
    </div>
</body>
</html>
