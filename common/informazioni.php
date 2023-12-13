<?php
try {
    $jsonFilePath = 'data/informazioni.json';
    $informazioni = readJsonFileToObject($jsonFilePath);

    // Output delle informazioni
    ?>
    <section id="informazioni">
        <h2>Le nostre informazioni:</h2>
        <p>Sede Legale: <?= htmlspecialchars($informazioni->sede_legale); ?></p>
        <p>Email: <?= htmlspecialchars($informazioni->email); ?></p>
        <p>Telefono: <?= htmlspecialchars($informazioni->telefono); ?></p>
        <p>Seguici su:
            <?php foreach ($informazioni->social as $socialName => $socialLink): ?>
                <a href="<?= htmlspecialchars($socialLink); ?>"><?= htmlspecialchars($socialName); ?></a>
            <?php endforeach; ?>
        </p>
    </section>
    <?php
} catch (Exception $e) {
    // In caso di errore, mostra il seguente contenuto statico:
    ?>
    <section id="informazioni">
        <h2>Le nostre informazioni:</h2>
        <p>Sede Legale: Via Esempio, 123 - 45678 Città (XY)</p>
        <p>Email: esempio@youcan.com</p>
        <p>Telefono: +39 012 345 6789</p>
        <p>Seguici su: <a href="#">Facebook</a>, <a href="#">Twitter</a>, <a href="#">Instagram</a></p>
    </section>
    <?php
}
?>