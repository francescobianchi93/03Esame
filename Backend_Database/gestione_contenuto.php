<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

function validate_and_sanitize($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password, $options);
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $azione = validate_and_sanitize($_POST['azione'] ?? '');

    if ($azione === 'Aggiungi') {
        $title = validate_and_sanitize($_POST['title'] ?? '');
        $img = validate_and_sanitize($_POST['img'] ?? '');
        $description = validate_and_sanitize($_POST['description'] ?? '');

        $stmt = $pdo->prepare("INSERT INTO caratteristiche (title, img, description) VALUES (?, ?, ?)");
        $stmt->execute([$title, $img, $description]);
    } elseif ($azione === 'Modifica') {
        $id = validate_and_sanitize($_POST['id'] ?? '');
        $title = validate_and_sanitize($_POST['title'] ?? '');
        $img = validate_and_sanitize($_POST['img'] ?? '');
        $description = validate_and_sanitize($_POST['description'] ?? '');

        $stmt = $pdo->prepare("UPDATE caratteristiche SET title = ?, img = ?, description = ? WHERE id = ?");
        $stmt->execute([$title, $img, $description, $id]);
    } elseif ($azione === 'Elimina') {
        $id = validate_and_sanitize($_POST['id'] ?? '');
        $stmt = $pdo->prepare("DELETE FROM caratteristiche WHERE id = ?");
        $stmt->execute([$id]);
    }
}

$stmt = $pdo->prepare("SELECT * FROM caratteristiche");
$stmt->execute();
$caratteristiche = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Caratteristiche</title>
    <link rel="stylesheet" href="./css_backend/style_contenuto.css"> 
</head>
<body>
    <h1>Gestione Caratteristiche</h1>

    <form method="post" action="">
        <input type="text" name="title" required placeholder="Titolo">
        <input type="text" name="img" required placeholder="Percorso immagine">
        <textarea name="description" required placeholder="Descrizione"></textarea>
        <input type="hidden" name="azione" value="Aggiungi">
        <input type="submit" value="Aggiungi Caratteristica">
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titolo</th>
                <th>Immagine</th>
                <th>Descrizione</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($caratteristiche as $caratteristica): ?>
            <tr>
                <td><?php echo htmlspecialchars($caratteristica['id']); ?></td>
                <td><?php echo htmlspecialchars($caratteristica['title']); ?></td>
                <td><?php echo htmlspecialchars($caratteristica['img']); ?></td>
                <td><?php echo htmlspecialchars($caratteristica['description']); ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($caratteristica['id']); ?>">
                        <input type="hidden" name="azione" value="Modifica">
                        <input type="submit" value="Modifica">
                    </form>
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($caratteristica['id']); ?>">
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
