<?php
session_start();
require_once 'db_config.php';

// Verifica se l'utente è amministratore
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Connessione al database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password, $options);
} catch (PDOException $e) {
    echo "Errore di connessione al database: " . $e->getMessage();
    exit;
}

// Ottieni i dati dei campi del modulo
$stmt = $pdo->prepare("SELECT * FROM modulo_contatti");
$stmt->execute();
$campi_modulo = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Funzione per la validazione e sanificazione dei dati
function validate_and_sanitize($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Gestione delle azioni
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['azione'])) {
        $azione = $_POST['azione'];
        
        if ($azione === 'AggiungiCampo') {
            $campo_nome = validate_and_sanitize($_POST['campo_nome']);
            $campo_valore = validate_and_sanitize($_POST['campo_valore']);
            
            if (!empty($campo_nome) && !empty($campo_valore)) {
                $stmt = $pdo->prepare("INSERT INTO modulo_contatti (campo_nome, valore_nome) VALUES (?, ?)");
                $stmt->execute([$campo_nome, $campo_valore]);
            }
        } elseif ($azione === 'ModificaCampo') {
            $campo_id = validate_and_sanitize($_POST['campo_id']);
            $campo_valore = validate_and_sanitize($_POST['campo_valore']);
            
            if (!empty($campo_id) && !empty($campo_valore)) {
                $stmt = $pdo->prepare("UPDATE modulo_contatti SET valore_nome = ? WHERE id = ?");
                $stmt->execute([$campo_valore, $campo_id]);
            }
        } elseif ($azione === 'EliminaCampo') {
            $campo_id = validate_and_sanitize($_POST['campo_id']);
            
            if (!empty($campo_id)) {
                $stmt = $pdo->prepare("DELETE FROM modulo_contatti WHERE id = ?");
                $stmt->execute([$campo_id]);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Modifiche Form</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Gestione Modifiche Form</h1>
    
    <!-- Aggiungi nuovo campo al modulo -->
    <h2>Aggiungi Nuovo Campo</h2>
    <form method="post" action="">
        <label for="campo_nome">Nome del Campo:</label>
        <input type="text" id="campo_nome" name="campo_nome" required>
        <label for="campo_valore">Valore del Campo:</label>
        <input type="text" id="campo_valore" name="campo_valore" required>
        <input type="hidden" name="azione" value="AggiungiCampo">
        <input type="submit" value="Aggiungi Campo">
    </form>
    
    <!-- Modifica/elimina campi esistenti -->
    <h2>Modifica/Elimina Campi Esistenti</h2>
    <ul>
        <?php foreach ($campi_modulo as $campo): ?>
            <li>
                <form method="post" action="">
                    <input type="text" name="campo_valore" value="<?php echo $campo['valore_nome']; ?>" required>
                    <input type="hidden" name="campo_id" value="<?php echo $campo['id']; ?>">
                    <input type="hidden" name="azione" value="ModificaCampo">
                    <input type="submit" value="Modifica">
                </form>
                <form method="post" action="">
                    <input type="hidden" name="campo_id" value="<?php echo $campo['id']; ?>">
                    <input type="hidden" name="azione" value="EliminaCampo">
                    <input type="submit" value="Elimina">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
