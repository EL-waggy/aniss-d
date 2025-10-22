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
  <link rel="icon" href="<?= BASE_URL ?>/img/index/icon_lofi_style.ico" sizes="any">
  <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/img/index/icon_lofi_style.ico">
  <link rel="apple-touch-icon" href="<?= BASE_URL ?>/img/index/icon_lofi_style.ico">
  <link rel="manifest" href="<?= BASE_URL ?>/site.webmanifest">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

  <meta name="theme-color" content="#fafafa">
  <meta name="description" content="Recette de la recette">

</head>
<body>

<!-- header navbar -->
<?php include $_SERVER['DOCUMENT_ROOT']."/partial/navbar.php"; ?>
<!-- end header navbar -->

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

  <!-- Footer -->
  <?php include $_SERVER['DOCUMENT_ROOT']."/partial/footer.php"; ?>
  <!-- End Footer -->

</body>
</html>
