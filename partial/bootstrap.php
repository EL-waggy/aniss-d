<?php
// URL base (pour les liens <a>, <img>, <link>…)
$baseUrl = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($baseUrl === '/' || $baseUrl === '\\') $baseUrl = '';
define('BASE_URL', $baseUrl);

// Chemin disque (pour les include/require)
$basePath = rtrim($_SERVER['DOCUMENT_ROOT'] . BASE_URL, '/');
define('BASE_PATH', $basePath);
