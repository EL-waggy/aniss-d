<?php
/**
 * index.php (recette) — Barre unique avec panneau déroulant SANS JS (hash anchors)
 * ---------------------------------------------------------------------------------
 * - Les puces de catégories sont des <a href="#cat-{slug}">.
 * - Le panneau correspondant s'affiche via :target en CSS.
 * - On garde aussi une classe "open" côté serveur si l'URL est /recette/{slug}/.
 * - Les cartes dans le <main> renvoient vers la même ancre (donc cliquables).
 */

// ---------- CONFIG ----------
$SITE_BASE       = '/';
$RECETTE_WEBROOT = '/recette';
$RECETTE_FSROOT  = __DIR__;

// ---------- HELPERS ----------
function listRecipeCategories(string $fsRoot): array {
  if (!is_dir($fsRoot)) return [];
  $cats = [];
  foreach (glob($fsRoot . '/*', GLOB_ONLYDIR) as $dir) {
    $slug = basename($dir);
    if ($slug === '' || $slug[0] === '_' || $slug === 'assets') continue;
    $count = count(glob($dir . '/*.html')) + count(glob($dir . '/*.php'));
    $label = ucfirst(str_replace(['-', '_'], ' ', $slug));
    $cats[] = ['slug'=>$slug,'label'=>$label,'count'=>$count];
  }
  usort($cats, fn($a,$b)=> strcmp($a['label'], $b['label']));
  return $cats;
}
function listRecipesInCategory(string $fsRoot, string $cat): array {
  $dir = rtrim($fsRoot,'/').'/'.$cat;
  if (!is_dir($dir)) return [];
  $items = [];
  foreach (['html','php'] as $ext) {
    foreach (glob($dir.'/*.'.$ext) as $file) {
      $base = basename($file);
      $slug = pathinfo($base, PATHINFO_FILENAME);
      $label = ucfirst(str_replace(['-', '_'], ' ', $slug));
      $items[] = [
        'slug'=>$slug,
        'label'=>$label,
        'ext'=>$ext,
        'url'=> '/recette/'.rawurlencode($cat).'/'.rawurlencode($slug).'.'.$ext
      ];
    }
  }
  usort($items, fn($a,$b)=> strcmp($a['label'],$b['label']));
  return $items;
}
function getActiveCategory(string $webroot): string {
  $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
  $segs = $path === '' ? [] : explode('/', $path);
  $root = trim($webroot, '/');
  $i = array_search($root, $segs, true);
  return ($i !== false && isset($segs[$i+1]) && $segs[$i+1] !== '') ? $segs[$i+1] : '';
}

// ---------- DATA ----------
$categories = listRecipeCategories($RECETTE_FSROOT);
$recipesByCat = [];
foreach ($categories as $c) $recipesByCat[$c['slug']] = listRecipesInCategory($RECETTE_FSROOT, $c['slug']);
$activeCat = getActiveCategory($RECETTE_WEBROOT);

// ---------- PAGE ----------
?><!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recettes - Aniss D.exe</title>
  <link rel="stylesheet" href="<?= $SITE_BASE ?>css/style.css">
  <style>
    .recette-bar{position:sticky;top:0;z-index:60;background:linear-gradient(135deg, rgba(255,255,255,.98), rgba(248,250,252,.98));border-bottom:1px solid #e5e7eb}
    .recette-bar .inner{display:grid;grid-template-columns:auto 1fr auto;gap:.5rem;align-items:center;max-width:1200px;margin:0 auto;padding:.5rem 1rem}
    .chips{display:flex;gap:.5rem;overflow-x:auto;scroll-snap-type:x proximity;padding:.25rem 0}
    .chip{flex:0 0 auto;scroll-snap-align:start;display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .85rem;border:1px solid #e5e7eb;border-radius:999px;background:#fff;font-size:.95rem;line-height:1;text-decoration:none;color:#111}
    .chip[aria-current="true"]{background:#111;color:#fff;border-color:#111}
    .count{opacity:.6;font-variant-numeric:tabular-nums}

    .recipes-panel{position:relative}
    .panel{position:absolute;left:0;right:0;top:0;background:#0b1220;color:#c9d1d9;border-bottom:1px solid #233046;box-shadow:0 24px 60px rgba(5,10,20,.45);display:none;z-index:65}
    .panel.open{display:block}             /* ouvert côté serveur */
    .panel:target{display:block}           /* ouvert via hash */
    .panel-inner{max-width:1200px;margin:0 auto;padding:14px 16px 18px}
    .panel-title{margin:4px 0 10px;color:#79c0ff;font-weight:700;letter-spacing:.05em;text-transform:uppercase}
    .panel-grid{display:grid;gap:10px;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr))}
    .panel-item a{display:block;padding:10px 12px;border:1px solid #233046;border-radius:10px;background:#111827;color:#e5e7eb;text-decoration:none}
    .panel-item a:hover{border-color:#3b82f6}

    .close-bar{display:flex;justify-content:flex-end;gap:8px;margin-top:6px}
    .close-link{font-size:.9rem;color:#9ca3af;text-decoration:none}
    .close-link:hover{text-decoration:underline}

    @media (max-width:780px){
      .recette-bar .inner{grid-template-columns:auto 1fr}
    }
    .grid{display:grid;gap:6px;grid-template-columns:repeat(auto-fit, minmax(220px,1fr));max-width:1100px;margin:1rem auto;padding:0 1rem}
    .card{background:#161b22;border:1px solid #2d333b;border-radius:14px;padding:10px;color:#c9d1d9}
    .card a{color:#79c0ff;text-decoration:none}
    .card a:hover{text-decoration:underline}
  </style>
</head>
<body>

<header class="site-header">
  <?php include "/partial/navbar.php"; ?>
  <!--
  <nav class="site-nav" aria-label="Navigation principale">
    <a href="<?= $SITE_BASE ?>" class="logo">Aniss D.exe</a>
    <ul class="nav-links">
      <li><a href="<?= $SITE_BASE ?>">Accueil</a></li>
      <li><a href="<?= $SITE_BASE ?>CV/">Mon CV</a></li>
      <li><a href="<?= $SITE_BASE ?>about/">À propos</a></li>
      <li><a href="<?= $SITE_BASE ?>Projets/">Projets</a></li>
      <li><a href="<?= $SITE_BASE ?>blog/">Blog</a></li>
      <li><a href="<?= $RECETTE_WEBROOT ?>/">Recettes</a></li>
      <li><a href="<?= $SITE_BASE ?>sport/">Sport</a></li>
      <li><a href="<?= $SITE_BASE ?>contact/">Contact</a></li>
      <li><a href="<?= $SITE_BASE ?>Politique%20de%20confidentialit%C3%A9/">Politique de confidentialité</a></li>
    </ul>
  </nav>
  -->
</header>

<!-- Barre unique des recettes -->
<div class="recette-bar" role="navigation" aria-label="Recettes">
  <div class="inner">
    <div class="chips" role="tablist" aria-label="Catégories">
      <?php foreach ($categories as $c): $slug=$c['slug']; $label=$c['label']; ?>
        <a class="chip" role="tab"
           aria-current="<?= $slug === $activeCat ? 'true' : 'false' ?>"
           href="#cat-<?= htmlspecialchars($slug) ?>">
          <?= htmlspecialchars($label) ?> <span class="count"><?= $c['count'] ?: '' ?></span>
        </a>
      <?php endforeach; ?>
      <?php if (empty($categories)): ?>
        <span>Aucune catégorie trouvée</span>
      <?php endif; ?>
    </div>
    <div></div>
  </div>

  <!-- Panneaux (un seul visible via :target ou .open) -->
  <div class="recipes-panel">
    <?php foreach ($categories as $c): $slug=$c['slug']; $label=$c['label']; $items=$recipesByCat[$slug] ?? []; ?>
      <div class="panel <?= $slug === $activeCat ? 'open' : '' ?>" id="cat-<?= htmlspecialchars($slug) ?>">
        <div class="panel-inner">
          <div class="panel-title"><?= htmlspecialchars($label) ?></div>
          <div class="panel-grid">
            <?php foreach ($items as $r): ?>
              <div class="panel-item"><a href="<?= htmlspecialchars($r['url']) ?>"><?= htmlspecialchars($r['label']) ?></a></div>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
              <p>Aucune recette trouvée dans <code>/recette/<?= htmlspecialchars($slug) ?></code>.</p>
            <?php endif; ?>
          </div>
          <div class="close-bar"><a class="close-link" href="#">Fermer</a></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<main>
  <section class="about" aria-labelledby="title-allcats">
    <h2 id="title-allcats">Toutes les catégories</h2>
    <div class="grid" role="list">
      <?php foreach ($categories as $c): ?>
        <article class="card" role="listitem">
          <h3><a href="#cat-<?= htmlspecialchars($c['slug']) ?>"><?= htmlspecialchars($c['label']) ?></a></h3>
          <p><a href="#cat-<?= htmlspecialchars($c['slug']) ?>"><?= $c['count'] ?> recette(s)</a></p>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
</main>

<footer class="site-footer">
  <div class="site-footer-content">
    <p>&copy; <?= date('Y') ?> Aniss D.exe · <a href="<?= $SITE_BASE ?>contact/" class="footer-link">Contact</a></p>
  </div>
</footer>

</body>
</html>
