<?php
$password = 'Fr4ncesco__'; // Sostituisci con la tua password scelta.
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password;
?>
