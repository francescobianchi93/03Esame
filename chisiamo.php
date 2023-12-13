<?php include './common/json_reader.php'; ?>
<?php include './common/header.chisiamo.php'; ?>
<?php include './common/header_content.php'; ?>

<body>
<?php
#commento
try {
    require_once './Backend_Database/db_config.php'; // Includi il file di configurazione del database

    // Connessione al database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password, $options);

    // Query per ottenere i dati dal database
    $stmt = $pdo->prepare("SELECT * FROM informazioni_sito");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data === false) {
        throw new Exception("Nessun dato trovato nel database.");
    }

    echo '<section id="chi-siamo">';
    echo '<div id="la-nostra-idea">';
    echo '<h1>' . htmlspecialchars($data['chi_siamo_titolo']) . '</h1>';
    echo '<p class="paragrafo-storia">' . nl2br(htmlspecialchars($data['chi_siamo_testo'])) . '</p>';
    echo '</div>';

    // Query per ottenere i dati sulla squadra dal database
    $stmt = $pdo->prepare("SELECT * FROM squadra");
    $stmt->execute();
    $squadra = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<div id="squadra">';
    echo '<article id="la-nostra-squadra">';
    echo '<h1>La Nostra Squadra</h1>';
    foreach ($squadra as $membro) {
        echo '<figure>';
        echo '<img src="' . htmlspecialchars($membro['immagine']) . '" alt="' . htmlspecialchars($membro['nome']) . '">';
        echo '<figcaption>' . htmlspecialchars($membro['nome']) . '</figcaption>';
        echo '</figure>';
    }
    echo '</article></div>';

    echo '<div id="vision">';
    echo '<article id="la-nostra-vision">';
    echo '<h1>' . htmlspecialchars($data['vision_titolo']) . '</h1>';
    echo '<p id="testo-pensiero">' . nl2br(htmlspecialchars($data['vision_testo'])) . '</p>';
    echo '</article></div>';
    echo '</section>';

} catch (Exception $e) {
    // Contenuto statico HTML come fallback
    ?>
    <section id="chi-siamo">
        <div id="la-nostra-idea">
            <h1>La nostra idea</h1>
            <p class="paragrafo-storia">...</p>
        </div>
        <div id="squadra">
            <article id="la-nostra-squadra">
                <h1>La Nostra Squadra</h1>
                <!-- Figure per ogni membro della squadra -->
            </article>
        </div>
        <div id="vision">
            <article id="la-nostra-vision">
                <h1>La Nostra Vision</h1>
                <p id="testo-pensiero">...</p>
            </article>
        </div>
    </section>
    <?php
}
?>

<?php include './common/footer.php'; ?>

</body>
</html>
