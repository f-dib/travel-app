<?php
// config/db.php

// Definizione delle credenziali di accesso al database
$dsn = "mysql:host=localhost;dbname=travel_app_db;charset=utf8";
$username = 'root';          // Nome utente di MySQL (sostituisci con il tuo utente)
$password = '';          // Password di MySQL (sostituisci con la tua password)

// Creazione di una variabile che conterrà l'oggetto PDO (connessione al database)
try {
    // Creazione di una nuova connessione PDO al database
    $db = new PDO($dsn, $username, $password);

    // Impostazione della modalità di errore di PDO su "Eccezione" (throw exceptions)
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Abilita l'uso di nomi di colonne associativi nell'output
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Messaggio di debug, da rimuovere in produzione
    // echo "Connessione al database riuscita!";

} catch (PDOException $e) {
    // Gestione degli errori: stampa un messaggio di errore e termina lo script
    echo "Errore di connessione al database: " . $e->getMessage();
    exit;
}
