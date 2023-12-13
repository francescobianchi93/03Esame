<footer>
    <?php
    try {
        $jsonFooterPath = 'data/db_footer.json';
        $footerData = readJsonFileToObject($jsonFooterPath);
        echo '<p class="footerhome">© ' . $footerData->anno . ' YouCan. Tutti i diritti riservati.</p>';
    } catch (Exception $e) {
        echo '<p class="footerhome">© 2023 YouCan. Tutti i diritti riservati.</p>';
    }
    ?>
</footer>