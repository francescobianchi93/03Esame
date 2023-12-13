<?php
session_start();
require_once 'db_config.php';

// Attiva la visualizzazione degli errori per il debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

function validate_and_sanitize($input) {
    if ($input === null) {
        return '';
    }
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['azione'])) {
    $titolo = isset($_POST['titolo']) ? validate_and_sanitize($_POST['titolo']) : '';
    $prezzo = isset($_POST['prezzo']) ? validate_and_sanitize($_POST['prezzo']) : '';
    $link_prova = isset($_POST['link_prova']) ? validate_and_sanitize($_POST['link_prova']) : '';
    $prezzo_prova = isset($_POST['prezzo_prova']) ? validate_and_sanitize($_POST['prezzo_prova']) : '';
    $sottotitolo = isset($_POST['sottotitolo']) ? validate_and_sanitize($_POST['sottotitolo']) : '';
    $link_mensile = isset($_POST['link_mensile']) ? validate_and_sanitize($_POST['link_mensile']) : '';
    $prezzo_mensile = isset($_POST['prezzo_mensile']) ? validate_and_sanitize($_POST['prezzo_mensile']) : '';


    if ($_POST['azione'] === 'Aggiungi') {
        $stmt = $pdo->prepare("INSERT INTO piani (titolo, prezzo, link_prova, prezzo_prova, sottotitolo, link_mensile, prezzo_mensile) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titolo, $prezzo, $link_prova, $prezzo_prova, $sottotitolo, $link_mensile, $prezzo_mensile]);
    } elseif ($_POST['azione'] === 'Modifica') {
        $id = validate_and_sanitize($_POST['id']);
        $stmt = $pdo->prepare("UPDATE piani SET titolo = ?, prezzo = ?, link_prova = ?, prezzo_prova = ?, sottotitolo = ?, link_mensile = ?, prezzo_mensile = ? WHERE id = ?");
        $stmt->execute([$titolo, $prezzo, $link_prova, $prezzo_prova, $sottotitolo, $link_mensile, $prezzo_mensile, $id]);
    } elseif ($_POST['azione'] === 'Elimina') {
        $id = validate_and_sanitize($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM piani WHERE id = ?");
        $stmt->execute([$id]);
    }
}

$stmt = $pdo->prepare("SELECT * FROM piani");
$stmt->execute();
$piani = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Piani</title>
    <link rel="stylesheet" href="./css_backend/style_piani.css">

</head>
<body>
    <h1>Gestione Piani</h1>

    <!-- Form per l'aggiunta di un nuovo piano -->
    <form method="post" action="">
        <input type="text" name="titolo" required placeholder="Titolo Piano">
        <input type="text" name="prezzo" required placeholder="Prezzo">
        <input type="text" name="link_prova" required placeholder="Link Prova">
        <input type="text" name="prezzo_prova" required placeholder="Prezzo Prova">
        <input type="text" name="sottotitolo" required placeholder="Sottotitolo">
        <input type="text" name="link_mensile" required placeholder="Link Mensile">
        <input type="text" name="prezzo_mensile" required placeholder="Prezzo Mensile">
        <input type="hidden" name="azione" value="Aggiungi">
        <input type="submit" value="Aggiungi">
    </form>

    <!-- Tabella per visualizzare i piani esistenti -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titolo</th>
                <th>Prezzo</th>
                <!-- Aggiungi qui altre intestazioni di colonna se necessario -->
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($piani as $piano): ?>
            <tr>
                <td><?php echo htmlspecialchars($piano['id']); ?></td>
                <td><?php echo htmlspecialchars($piano['titolo']); ?></td>
                <td><?php echo htmlspecialchars($piano['prezzo']); ?></td>
                <!-- Aggiungi qui altre celle se necessario -->
                <td>
                  <!-- Form per la modifica di un piano esistente -->
                    <form method="post" action="gestione_piani.php">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($piano['id']); ?>">
                        <input type="text" name="titolo" required value="<?php echo htmlspecialchars($piano['titolo']); ?>" placeholder="Titolo Piano">
                        <input type="text" name="prezzo" required value="<?php echo htmlspecialchars($piano['prezzo']); ?>" placeholder="Prezzo">
                        <input type="text" name="link_prova" required value="<?php echo htmlspecialchars($piano['link_prova']); ?>" placeholder="Link Prova">
                        <input type="text" name="prezzo_prova" required value="<?php echo htmlspecialchars($piano['prezzo_prova']); ?>" placeholder="Prezzo Prova">
                        <input type="text" name="sottotitolo" required value="<?php echo htmlspecialchars($piano['sottotitolo']); ?>" placeholder="Sottotitolo">
                        <input type="text" name="link_mensile" required value="<?php echo htmlspecialchars($piano['link_mensile']); ?>" placeholder="Link Mensile">
                        <input type="text" name="prezzo_mensile" required value="<?php echo htmlspecialchars($piano['prezzo_mensile']); ?>" placeholder="Prezzo Mensile">
                        <input type="hidden" name="azione" value="Modifica">
                        <input type="submit" value="Salva Modifiche">
</form>

                    <!-- Form per l'eliminazione di un piano -->
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo $piano['id']; ?>">
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
