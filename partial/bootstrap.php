<?php
// ---------------------------------------------------------
// bootstrap.php
// Configuration globale des chemins pour le site PHP
// ---------------------------------------------------------

// 1️⃣ Définir l’URL de base du site
// ---------------------------------------------------------
// Si ton site est à la racine de ton domaine (https://aniss-d.ovh)
// -> laisse la chaîne vide ''
// Si en local tu le lances via http://localhost/aniss-d/
// -> mets '/aniss-d'
$BASE_URL = ''; // ⚠️ adapte si ton site local est dans un sous-dossier
define('BASE_URL', $BASE_URL);


// 2️⃣ Définir le chemin absolu du serveur
// ---------------------------------------------------------
// Permet d’inclure des fichiers peu importe la page courante
define('BASE_PATH', rtrim($_SERVER['DOCUMENT_ROOT'], '/'));

// 3️⃣ Fonction utilitaire pour générer des liens corrects
// ---------------------------------------------------------
if (!function_exists('asset')) {
  function asset(string $path): string {
    return BASE_URL . '/' . ltrim($path, '/');
  }
}

// 4️⃣ Fonction utilitaire pour inclure facilement les partiels
// ---------------------------------------------------------
if (!function_exists('include_partial')) {
  function include_partial(string $file): void {
    include BASE_PATH . '/partial/' . $file;
  }
}
