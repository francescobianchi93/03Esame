<?php include './common/json_reader.php'; ?>
<?php include './common/header.contatti.php'; ?>
<?php include './common/header_content.php'; ?>

<?php
$mostraForm = true; // Continua a mostrare il form dopo l'invio o meno
$nomeErr = $cognomeErr = $emailErr = $telefonoErr = $messaggioErr = "";
$nome = $cognome = $email = $telefono = $messaggio = "";
$errori = [];
$nome_cartella = "Dati Form";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = strip_tags(trim($_POST["nome"]));
    $cognome = strip_tags(trim($_POST["cognome"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $telefono = strip_tags(trim($_POST["telefono"]));
    $messaggio = strip_tags(trim($_POST["messaggio"]));

    if (empty($nome)) {
        $nomeErr = "Il campo Nome è obbligatorio.";
        $errori[] = $nomeErr;
    }

    if (empty($cognome)) {
        $cognomeErr = "Il campo Cognome è obbligatorio.";
        $errori[] = $cognomeErr;
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Inserisci un'email valida.";
        $errori[] = $emailErr;
    }

    if (!preg_match('/^\d{10}$/', $telefono)) {
        $telefonoErr = "Scrivi un numero di telefono valido.";
        $errori[] = $telefonoErr;
    }

    if (empty($messaggio)) {
        $messaggioErr = "Il campo Messaggio è obbligatorio.";
        $errori[] = $messaggioErr;
    }

    if (count($errori) === 0) {
        // Assicurati che la cartella esista, se non esiste, creala
        $percorso_cartella = __DIR__ . DIRECTORY_SEPARATOR . $nome_cartella;
        $nome_file = $percorso_cartella . DIRECTORY_SEPARATOR . 'dati_utenti.json';

        if (!file_exists($percorso_cartella) && !mkdir($percorso_cartella, 0755, true)) {
            $errori[] = "Impossibile creare la cartella di destinazione. Per favore, controlla i permessi del tuo server.";
        }

        // Se la cartella esiste o è stata creata, procedi con il salvataggio del file
        if (count($errori) === 0) {
            $dati_esistenti = file_exists($nome_file) ? json_decode(file_get_contents($nome_file), true) : [];
            $dati_esistenti[] = compact('nome', 'cognome', 'email', 'telefono', 'messaggio');

            if (file_put_contents($nome_file, json_encode($dati_esistenti, JSON_PRETTY_PRINT))) {
                echo 'Dati salvati con successo.';
                $mostraForm = false;
            } else {
                $errori[] = "Errore nel salvataggio dei dati. Riprova più tardi.";
            }
        }
    }
}


?>
<body>
    
<section id="contattaci">
        <h1>Contattaci</h1>
        <?php if ($mostraForm): ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" class="<?php echo $nomeErr ? 'error-input' : ''; ?>" required>
                    <span class="error"><?php echo $nomeErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="cognome">Cognome:</label>
                    <input type="text" id="cognome" name="cognome" value="<?php echo htmlspecialchars($cognome); ?>" class="<?php echo $cognomeErr ? 'error-input' : ''; ?>" required>
                    <span class="error"><?php echo $cognomeErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="<?php echo $emailErr ? 'error-input' : ''; ?>" required>
                    <span class="error"><?php echo $emailErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="telefono">Telefono:</label>
                    <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" class="<?php echo $telefonoErr ? 'error-input' : ''; ?>" pattern="\d{10}">
                    <span class="error"><?php echo $telefonoErr; ?></span>
                </div>

                <div class="form-group">
                    <label for="messaggio">Messaggio:</label>
                    <textarea id="messaggio" name="messaggio" class="<?php echo $messaggioErr ? 'error-input' : ''; ?>"><?php echo htmlspecialchars($messaggio); ?></textarea>
                    <span class="error"><?php echo $messaggioErr; ?></span>
                </div>

                <input type="submit" value="Invia">
            </form>
        <?php else: ?>
            <strong>Grazie per averci contattato, le risponderemo a breve.</strong>
        <?php endif; ?>
    </section>

    <?php include './common/informazioni.php' ;?>
    <?php include './common/footer.php' ;?>

</body>
