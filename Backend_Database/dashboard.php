<?php
session_start();
require_once 'db_config.php';

// Controlla se l'utente è loggato, altrimenti reindirizza alla pagina di login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Amministratore</title>
    <link rel="stylesheet" href="./css_backend/style_dashboard.css"> 

</head>
<body>
    <div class="dashboard-container">
        <h1>Dashboard Amministratore</h1>
        <p>Benvenuto, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

        <div class="dashboard-menu">
            <ul>
                <li><a href="gestione_utenti.php">Gestione Utenti</a></li>
                <li><a href="gestione_piani.php">Gestione Piani (Home)</a></li>
                <li><a href="gestione_recensioni.php">Gestione recensioni(Home)</a></li>
                <li><a href="gestione_caratteristiche.php">Gestione caratteristiche (Home)</a></li>
                <li><a href="gestione_contenuto.php">Gestione contenuto (Home)</a></li>
                <br>
                <br>
                <br>
                <li><a href="gestione_chi_siamo.php">Gestione idea (chi siamo)</a></li>
                
                <br>
                <br>
                <br>
                <li><a href="gestione_form.php">Gestione form (contatti)</a></li>
                <li><a href="gestione_modifiche_form.php">Gestione modifiche del form (contatti)</a></li>
            </ul>
        </div>
        
        <!-- Qui puoi inserire altre sezioni della dashboard -->
        
    </div>
</body>
</html>

