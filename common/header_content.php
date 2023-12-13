<?php
try {
    $jsonFilePath = 'data/db_contenuto.json';
    $data = json_decode(file_get_contents($jsonFilePath), true);

    // Usiamo il contenuto del JSON per generare dinamicamente l'HTML
    echo '<header id="header"><main><div id="contenitore"><div id="logo">';
    echo '<img src="'.$data['logo']['src'].'" alt="'.$data['logo']['alt'].'"/>';
    echo '</div><nav id="menu"><ul>';
    foreach ($data['menu'] as $menuItem) {
        echo '<li><a href="'.$menuItem['link'].'">'.$menuItem['testo'].'</a></li>';
    }
    echo '</ul></nav></div></main></header>';

} catch (Exception $e) {
    // Se si verifica un errore, stampa a video html statico
    ?>

    <header id="header">
        <main>
            <div id="contenitore">
                <div id="logo">
                    <img src="logo.png" alt="Logo YouCan"/>
                </div>
                <nav id="menu">
                    <ul>
                        <li><a href="homepage.php">Home</a></li>
                        <li><a href="chisiamo.php">Chi siamo</a></li>
                        <li><a href="contatti.php">Contatti</a></li>
                        <li><a href="https://courses.youcanmath.com/login">Login</a></li>
                    </ul>
                </nav>
            </div>
        </main>
    </header>

    <?php
}
?>
