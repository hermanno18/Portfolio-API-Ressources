<?php
// Charger la fonction de seed pour les outils
require_once(__DIR__ . '/Tools/Tools.php');

// Charger la fonction de seed pour les contacts
require_once(__DIR__ . '/Contacts/Contacts.php');

// Appeler les fonctions de seed
seed_tools();
seed_contacts();
