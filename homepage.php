<?php include './common/json_reader.php'; ?>
<?php include './common/header.home.php'; ?>
<?php include './common/header_content.php'; ?>
<?php require_once './Backend_Database/db_config.php'; // Sostituisci con il percorso corretto ?>

<body>

<?php
try {
    $stmt = $pdo->query("SELECT titolo_sezione, video_src, video_fallback_text, bottone_testo, bottone_link FROM contenuto");
    $contenuto = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $contenuto = false;
}

if ($contenuto) {
    echo '<section id="contenuto"><div id="side-container"><div id="left-side"><article id="testo">';
    echo '<div class="box"><h1 class="h1-1">'.htmlspecialchars($contenuto['titolo_sezione']).'</h1></div>';    
    echo '</article></div><div id="right-side"><div class="video-container">';
    echo '<video width="320" height="240" controls class="video-size"><source src="'.htmlspecialchars($contenuto['video_src']).'" type="video/mp4">';
    echo htmlspecialchars($contenuto['video_fallback_text']).'</video></div>';
    echo '<a href="'.htmlspecialchars($contenuto['bottone_link']).'" class="btn">'.htmlspecialchars($contenuto['bottone_testo']).'</a></div></div></section>';
} else {
    // HTML statico come backup
    ?>
    <section id="contenuto">        
        <div id="side-container">        
            <div id="left-side">
                <article id="testo">
                    <div class="box">
                        <h1 class="h1-1">Perché scegliere Youcan?</h1>
                    </div>    
                </article>
            </div>
            <div id="right-side">
                <div class="video-container">
                    <video width="320" height="240" controls class="video-size">
                        <source src="path/to/tuo_video.mp4" type="video/mp4">
                        Il tuo browser non supporta il tag video.
                    </video>
                </div>
                <a href="path/to/link_alla_tua_pagina.html" class="btn">Scarica l'App</a>
            </div>
        </div>
    </section>
    <?php
}
?>

<section id="caratteristiche">
    <?php
    try {
        $stmt = $pdo->query("SELECT img, title, description FROM caratteristiche");
        $caratteristiche = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $caratteristiche = false;
    }

    if ($caratteristiche) {
        foreach ($caratteristiche as $caratteristica) {
            echo '<div class="box-caratteristica">';
            echo '<img src="' . htmlspecialchars($caratteristica['img']) .  '" alt="Descrizione Immagine">';
            echo '<h1 class="h1-2">' . htmlspecialchars($caratteristica['title']) .  '</h1>';
            echo '<p>' . htmlspecialchars($caratteristica['description']) . "</p>";
            echo '</div>';
        }
    } else {
        // HTML statico come backup
        ?>
        <div class="box-caratteristica">
            <img src="path/to/icons8-location.gif" alt="Descrizione Immagine 1">
            <h1 class="h1-2">Sempre disponibile</h1>
            <p class="p-1">Accessibile H24, migliaia di videolezioni, correttore di esercizi fotografico, Intelligenza artificiale a disposizione, esercitazioni illimitate</p>
        </div>
        <div class="box-caratteristica">
            <img src="path/to/icons8-services.gif" alt="Descrizione Immagine 2">
            <h1 class="h1-3">Personalizzato</h1>
            <p class="p-2">YouCan è uno strumento concepito per adattarsi a tutte le tue esigenze</p>
        </div>
        <div class="box-caratteristica">
            <img src="path/to/icons8-clock.gif" alt="Descrizione Immagine 3">
            <h1 class="h1-4">Disdici quando vuoi</h1>
            <p class="p-3">Abbonati e disdici quando vuoi. Con YouCan non hai vincoli</p>
        </div>
        <?php
    }
    ?>
</section>

<section id="recensioni">
    <h2 class="h2-recensione">Le recensioni dei nostri studenti</h2>
    <div id="video-recensioni">
    <?php
    try {
        $stmt = $pdo->query("SELECT src, nome FROM recensioni WHERE tipo = 'video'");
        $video_recensioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $video_recensioni = false;
    }

    if ($video_recensioni) {
        foreach ($video_recensioni as $video) {
            echo '<article class="video">';
            echo '<video src="' . htmlspecialchars($video['src']) . '" controls></video>';
            echo '<p>' . htmlspecialchars($video['nome']) . '</p>';
            echo '</article>';
        }
    } else {
        // HTML statico come backup
        ?>
        <article class="video">
            <video src="path/to/percorso/video1.mp4" controls></video>
            <p>Nome Studente 1</p>
        </article>
        <article class="video">
            <video src="path/to/percorso/video2.mp4" controls></video>
            <p>Nome Studente 2</p>
        </article>
        <article class="video">
            <video src="path/to/percorso/video3.mp4" controls></video>
            <p>Nome Studente 3</p>
        </article>
        <?php
    }
    ?>
    </div>

    <div id="testo-recensioni">
    <?php
    try {
        $stmt = $pdo->query("SELECT nome, testo FROM recensioni WHERE tipo = 'testo'");
        $testo_recensioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $testo_recensioni = false;
    }

    if ($testo_recensioni) {
        foreach ($testo_recensioni as $testoRecensione) {
            echo '<article class="recensione">';
            echo '<p><strong>' . htmlspecialchars($testoRecensione['nome']) . ':</strong> ' . htmlspecialchars($testoRecensione['testo']) . '</p>';
            echo '</article>';
        }
    } else {
        // HTML statico come backup
        ?>
        <article class="recensione">
            <p><strong>Nome Cliente 1:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>
        </article>
        <article class="recensione">
            <p><strong>Nome Cliente 2:</strong> Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet.</p>
        </article>
        <article class="recensione">
            <p><strong>Nome Cliente 3:</strong> Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta.</p>
        </article>
        <?php
    }
    ?>
    </div>

</section>

<section id="piani">
    <h2 class="titolo-sezione">Scegli il piano più adatto a te</h2>
    <div id="container-piani">
    <?php
    try {
        $stmtPiani = $pdo->query("SELECT id, titolo, prezzo, link_prova, prezzo_prova, sottotitolo, link_mensile, prezzo_mensile FROM piani");
        $piani = $stmtPiani->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($piani as $piano) {
            echo '<div class="piano">';
            echo '<h3 class="titolo-piano">' . htmlspecialchars($piano['titolo']) . '</h3>';
            echo '<p class="prezzo">' . htmlspecialchars($piano['prezzo']) . '</p>';
            
            $stmtFeatures = $pdo->prepare("SELECT feature FROM features_piani WHERE piano_id = ?");
            $stmtFeatures->execute([$piano['id']]);
            $features = $stmtFeatures->fetchAll(PDO::FETCH_ASSOC);
            
            echo '<ul class="lista-features">';
            foreach ($features as $feature) {
                echo '<li class="feature">' . htmlspecialchars($feature['feature']) . '</li>';
            }
            echo '</ul>';
            
            echo '<a href="' . htmlspecialchars($piano['link_prova']) . '" class="bottone prova-settimanale">' . htmlspecialchars($piano['prezzo_prova']) . '</a>';
            echo '<p class="sottotitolo">' . htmlspecialchars($piano['sottotitolo']) . '</p>';
            echo '<a href="' . htmlspecialchars($piano['link_mensile']) . '" class="bottone piano-mensile">' . htmlspecialchars($piano['prezzo_mensile']) . '</a>';
            echo '</div>';
        }
    } catch (Exception $e) {
        echo "<p>Errore durante il caricamento dei piani: " . $e->getMessage() . "</p>";
        // HTML statico come backup
        ?>
       
       <!-- Qui inserisci l'HTML statico per i piani di abbonamento -->
<div class="piano-statico">
    <h3 class="titolo-piano">Standard</h3>
    <p class="prezzo">€8,99/mese</p>
    <ul class="lista-features">
        <li class="feature">1000 Videolezioni</li>
        <li class="feature">1 dispositivo su cui utilizzare la piattaforma</li>
        <li class="feature">Assistenza tramite IA (2 richieste al giorno)</li>
        <li class="feature">Correttore fotografico degli esercizi (2 richieste al giorno)</li>
    </ul>
    <a href="link-di-acquisto" class="bottone prova-settimanale">Prova Settimanale a €2,99</a>
    <p class="sottotitolo">Dopo 7 giorni €8,99/mese</p>
    <a href="link-di-acquisto" class="bottone piano-mensile">Piano Mensile a €8.99/mese</a>
</div>

<div class="piano-statico">
    <h3 class="titolo-piano">Premium</h3>
    <p class="prezzo">€12,99/mese</p>
    <ul class="lista-features">
        <li class="feature">Oltre 2000 Videolezioni</li>
        <li class="feature">2 dispositivi su cui utilizzare la piattaforma</li>
        <li class="feature">Assistenza tramite IA (5 richieste al giorno)</li>
        <li class="feature">Correttore fotografico degli esercizi (5 richieste al giorno)</li>
    </ul>
    <a href="link-di-acquisto" class="bottone prova-settimanale">Prova Settimanale a €4,99</a>
    <p class="sottotitolo">Dopo 7 giorni €12,99/mese</p>
    <a href="link-di-acquisto" class="bottone piano-mensile">Piano Mensile a €12,99/mese</a>
</div>

<div class="piano-statico">
    <h3 class="titolo-piano">Platinum</h3>
    <p class="prezzo">€19,99/mese</p>
    <ul class="lista-features">
        <li class="feature">Accesso a tutte le Videolezioni</li>
        <li class="feature">3 dispositivi su cui utilizzare la piattaforma</li>
        <li class="feature">Assistenza tramite IA (illimitata)</li>
        <li class="feature">Correttore fotografico degli esercizi (illimitato)</li>
    </ul>
    <a href="link-di-acquisto" class="bottone prova-settimanale">Prova Settimanale a €6,99</a>
    <p class="sottotitolo">Dopo 7 giorni €19,99/mese</p>
    <a href="link-di-acquisto" class="bottone piano-mensile">Piano Mensile a €19,99/mese</a>
</div>


        <?php
    }
    ?>
    </div>
</section>

<?php include './common/footer.php'; ?>
</body>

