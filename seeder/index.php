<?php
// Charger la fonction de seed pour les outils
require_once(__DIR__ . '/Tools/Tools.php');

// Charger la fonction de seed pour les contacts
require_once(__DIR__ . '/Contacts/Contacts.php');

// Charger la fonction de seed pour les contacts
require_once(__DIR__ . '/Projects/Projects.php');

// Charger la fonction de seed pour les contacts
require_once(__DIR__ . '/Companies/Companies.php');

// Appeler les fonctions de seed
seed_tools();
seed_contacts(); // --pb
seed_projects(); // pp
seed_companies();// pb
