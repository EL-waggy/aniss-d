<?php
/**
 * index.php (recette) — contrôleur unique
 * ------------------------------------------------------------
 * - Barrette de navigation "catégories" fonctionnelle
 * - Liste la catégorie cliquée (ex: /recette/dessert/)
 * - Lien vers chaque page recette (HTML/PHP) dans la catégorie
 * - Redirige automatiquement si l’URL pointe déjà sur une recette
 *
 * PRÉREQUIS .htaccess (extraits) :
 *   # Router tout ce qui commence par /recette vers ce fichier
 *   RewriteRule ^recette(?:/.*)?$ /recette/index.php [L,QSA]
 *
 * NOTE : on NE "inclut" pas les fichiers HTML de recette dans ce template,
 *        on y fait simplement des liens. Si vous voulez server-side render,
 *        il faudra passer vos recettes en fragments (partials).
 */

// ---------- CONFIG (chemins sûrs OVH) ----------
$SITE_BASE       = '/';         // racine du site (où il y a /css, /img, etc.)
$RECETTE_WEBROOT = '/recette';  // URL racine des recettes
$RECETTE_FSROOT  = __DIR__;     // chemin disque du dossier /recette

// ---------- HELPERS ----------

/**
 * Retourne les catégories présentes comme sous-dossiers de /recette.
 */
function listRecipeCategories(string $fsRoot): array {
  if (!is_dir($fsRoot)) return [];
  $cats = [];
  foreach (glob($fsRoot . '/*', GLOB_ONLYDIR) as $dir) {
    $slug = basename($dir);
    if ($slug === '' || $slug[0] === '_' || $slug === 'assets') continue; // ignore techniques
    // Compte recettes .html et .php dans la catégorie
    $count = count(glob($dir . '/*.html')) + count(glob($dir . '/*.php'));
    $label = ucfirst(str_replace(['-', '_'], ' ', $slug));
    $cats[] = ['slug'=>$slug, 'label'=>$label, 'count'=>$count];
  }
  usort($cats, fn($a,$b)=> strcmp($a['label'], $b['label']));
  return $cats;
}

/**
 * Liste les recettes d'une catégorie (fichiers .html et .php)
 */
function listRecipesInCategory(string $fsRoot, string $cat): array {
  $dir = rtrim($fsRoot, '/').'/'.$cat;
  if (!is_dir($dir)) return [];
  $items = [];
  foreach (['html','php'] as $ext) {
    foreach (glob($dir.'/*.'.$ext) as $file) {
      $base = basename($file);
      $slug = pathinfo($base, PATHINFO_FILENAME);
      $label = ucfirst(str_replace(['-', '_'], ' ', $slug));
      $items[$slug] = [
        'slug'  => $slug,
        'label' => $label,
        'ext'   => $ext,
      ];
    }
  }
  // valeurs uniques par slug
  ksort($items);
  // transforme en tableau indexé + ajoute l'URL web
  return array_values(array_map(function($it) use ($cat){
    $it['url'] = '/recette/'.rawurlencode($cat).'/'.rawurlencode($it['slug']).'.'.$it['ext'];
    return $it;
  }, $items));
}

/**
 * Catégorie active déduite de l'URL (ex: /recette/dessert/ -> dessert)
 */
function getActiveCategory(string $webroot): string {
  $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
  $segments = $path === '' ? [] : explode('/', $path);
  $root = trim($webroot, '/');
  $i = array_search($root, $segments, true);
  return ($i !== false && isset($segments[$i+1]) && $segments[$i+1] !== '') ? $segments[$i+1] : '';
}

/**
 * Si l’URL est de forme /recette/{cat}/{slug}, on redirige vers le fichier réel
 * (recette HTML/PHP) pour l’afficher tel quel (pages autonomes).
 */
function maybeRedirectToRecipeFile(string $fsRoot, string $webroot): void {
  $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
  $segments = $path === '' ? [] : explode('/', $path);
  $root = trim($webroot, '/');
  $i = array_search($root, $segments, true);
  if ($i === false) return;

  // /recette/{cat}/{slug}
  if (isset($segments[$i+1], $segments[$i+2]) && $segments[$i+1] !== '' && $segments[$i+2] !== '') {
    $cat = $segments[$i+1];
    $slug = $segments[$i+2];
    $dir = rtrim($fsRoot,'/').'/'.$cat;
    foreach (['html','php'] as $ext) {
      $f = $dir.'/'.$slug.'.'.$ext;
      if (is_file($f)) {
        $target = $webroot.'/'.rawurlencode($cat).'/'.rawurlencode($slug).'.'.$ext;
        header('Location: '.$target, true, 302);
        exit;
      }
    }
    // sinon: laisser l'affichage 404 custom plus bas
  }
}

/**
 * Fil d’Ariane basique
 */
function renderBreadcrumb(string $webroot, string $pageTitle = ''): string {
  $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
  $segments = $path === '' ? [] : explode('/', $path);
  $root = trim($webroot, '/');
  $out = [];
  $out[] = '<a href="/">Accueil</a>';
  $out[] = '<a href="'.htmlspecialchars($webroot).'/">Recettes</a>';
  $i = array_search($root, $segments, true);
  if ($i !== false && isset($segments[$i+1]) && $segments[$i+1] !== '') {
    $catSlug  = $segments[$i+1];
    $catLabel = ucfirst(str_replace(['-', '_'], ' ', $catSlug));
    $out[] = '<a href="'.htmlspecialchars($webroot).'/'.rawurlencode($catSlug).'/">'.htmlspecialchars($catLabel).'</a>';
  }
  if ($pageTitle) $out[] = htmlspecialchars($pageTitle);
  return implode(' / ', $out);
}

// ---------- DATA ----------
$categories = listRecipeCategories($RECETTE_FSROOT);
$activeCat  = getActiveCategory($RECETTE_WEBROOT);

// Si l'URL vise déjà une recette précise, on redirige vers le fichier réel
maybeRedirectToRecipeFile($RECETTE_FSROOT, $RECETTE_WEBROOT);

// ---------- PAGE METADATA ----------
$pageTitle = $activeCat ? 'Catégorie : '.ucfirst(str_replace(['-','_'],' ', $activeCat)) : 'Toutes les catégories';
$pageDescription = "Recettes du site Aniss D.exe";

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recettes - Aniss D.exe</title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
  <link rel="stylesheet" href="<?= $SITE_BASE ?>css/style.css">
  <link rel="icon" href="<?= $SITE_BASE ?>img/index/image-principale-1.png" sizes="any">
  <style>
    /* Styles minimum de la sous-nav si le CSS global n'est pas chargé */
    .subnav-recette{position:sticky;top:0;z-index:50;background:#fff;border-bottom:1px solid #eee}
    .subnav-recette .subnav-inner{display:grid;grid-template-columns:auto 1fr auto auto;gap:.5rem;align-items:center;padding:.5rem 1rem;max-width:1200px;margin:0 auto}
    .subnav-recette .subnav-scroll{display:flex;gap:.5rem;overflow-x:auto;scroll-snap-type:x proximity;padding:.25rem 0}
    .subnav-recette .chip{flex:0 0 auto;scroll-snap-align:start;display:inline-flex;align-items:center;gap:.4rem;padding:.4rem .8rem;border:1px solid #e5e7eb;border-radius:999px;background:#fafafa;font-size:.95rem;line-height:1;text-decoration:none;color:#111}
    .subnav-recette .chip[aria-selected="true"]{background:#111;color:#fff;border-color:#111}
    .subnav-recette .chip .count{opacity:.6;font-variant-numeric:tabular-nums}
    .subnav-recette .subnav-tools{display:flex;gap:.5rem;align-items:center}
    .subnav-recette input[type="search"],.subnav-recette select{padding:.4rem .6rem;border:1px solid #e5e7eb;border-radius:.5rem;font-size:.95rem}
    .subnav-recette .subnav-arrow{border:1px solid #e5e7eb;background:#fff;border-radius:.5rem;padding:.35rem .55rem;cursor:pointer}
    @media (max-width:780px){
      .subnav-recette .subnav-inner{grid-template-columns:auto 1fr auto}
      .subnav-recette .subnav-tools{grid-column:1 / -1}
    }
    .grid{display:grid;gap:16px;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));margin:1rem auto;max-width:1100px;padding:0 1rem}
    .card{background:#161b22;border:1px solid #2d333b;border-radius:14px;padding:16px;color:#c9d1d9}
    .card a{color:#79c0ff;text-decoration:none}
    .card a:hover{text-decoration:underline}
    .empty{max-width:1100px;margin:1rem auto;padding:0 1rem;color:#6b7280}
    .breadcrumb{max-width:1200px;margin:.5rem auto 0;padding:0 1rem;font-size:.9rem;color:#6b7280}
    .breadcrumb a{color:inherit;text-decoration:underline;text-underline-offset:2px}
  </style>
</head>
<body>

<header class="site-header">
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
</header>

<!-- Sous-navigation Recettes -->
<nav class="subnav-recette" aria-label="Navigation des catégories de recettes">
  <div class="subnav-inner">
    <button class="subnav-arrow left" aria-label="Défiler vers la gauche" hidden>◀</button>

    <div class="subnav-scroll" role="tablist" aria-label="Catégories">
      <?php foreach ($categories as $c): ?>
        <a class="chip" role="tab"
           aria-selected="<?= $c['slug'] === $activeCat ? 'true' : 'false' ?>"
           href="<?= $RECETTE_WEBROOT . '/' . rawurlencode($c['slug']) . '/' ?>">
          <?= htmlspecialchars($c['label']) ?>
          <span class="count"><?= $c['count'] ?: '' ?></span>
        </a>
      <?php endforeach; ?>
      <?php if (empty($categories)): ?>
        <span>Aucune catégorie trouvée dans <code><?= htmlspecialchars($RECETTE_WEBROOT) ?></code></span>
      <?php endif; ?>
    </div>

    <div class="subnav-tools">
      <input type="search" id="search-recette" placeholder="Rechercher une recette…" aria-label="Rechercher une recette">
      <select id="filter-difficulte" aria-label="Filtrer par difficulté">
        <option value="">Difficulté</option>
        <option value="facile">Facile</option>
        <option value="moyenne">Moyenne</option>
        <option value="difficile">Difficile</option>
      </select>
      <button class="subnav-arrow right" aria-label="Défiler vers la droite">▶</button>
    </div>
  </div>
</nav>

<p class="breadcrumb" aria-label="Fil d’Ariane"><?= renderBreadcrumb($RECETTE_WEBROOT, $activeCat ? '' : ''); ?></p>

<main>
  <?php if (!$activeCat): ?>
    <!-- VUE : page Recettes (toutes les catégories) -->
    <section class="about" aria-labelledby="title-allcats">
      <h2 id="title-allcats">Toutes les catégories</h2>
      <div class="grid" role="list">
        <?php foreach ($categories as $c): ?>
          <article class="card" role="listitem">
            <h3><a href="<?= $RECETTE_WEBROOT . '/' . rawurlencode($c['slug']) . '/' ?>">
                <?= htmlspecialchars($c['label']) ?></a></h3>
            <p><?= $c['count'] ?> recette(s)</p>
          </article>
        <?php endforeach; ?>
      </div>
      <?php if (empty($categories)): ?>
        <p class="empty">Ajoutez des sous-dossiers (ex: <code>/recette/dessert</code>) contenant des fichiers <code>.html</code> ou <code>.php</code>.</p>
      <?php endif; ?>
    </section>

  <?php else: ?>
    <!-- VUE : page Catégorie -->
    <?php $recipes = listRecipesInCategory($RECETTE_FSROOT, $activeCat); ?>
    <section class="about" aria-labelledby="title-cat">
      <h2 id="title-cat">Catégorie : <?= htmlspecialchars(ucfirst(str_replace(['-','_'],' ', $activeCat))) ?></h2>
      <div class="grid" role="list">
        <?php foreach ($recipes as $r): ?>
          <article class="card" role="listitem">
            <h3><a href="<?= htmlspecialchars($r['url']) ?>"><?= htmlspecialchars($r['label']) ?></a></h3>
            <p><?= strtoupper($r['ext']) ?> · <code><?= htmlspecialchars($r['slug']) ?></code></p>
          </article>
        <?php endforeach; ?>
      </div>
      <?php if (empty($recipes)): ?>
        <p class="empty">Aucune recette trouvée dans <code>/recette/<?= htmlspecialchars($activeCat) ?></code>. Déposez vos fichiers <code>.html</code> ou <code>.php</code> dans ce dossier.</p>
      <?php endif; ?>
    </section>
  <?php endif; ?>
</main>

<footer class="site-footer">
  <div class="site-footer-content">
    <p>&copy; <?= date('Y') ?> Aniss D.exe · <a href="<?= $SITE_BASE ?>contact/" class="footer-link">Contact</a></p>
  </div>
</footer>

<script>
  // Accessibilité & confort pour la sous-nav
  (() => {
    const scroll = document.querySelector('.subnav-scroll');
    const leftBtn = document.querySelector('.subnav-arrow.left');
    const rightBtn = document.querySelector('.subnav-arrow.right');
    const chips = document.querySelectorAll('.subnav-scroll .chip');

    if (scroll && leftBtn && rightBtn) {
      const toggleArrows = () => {
        leftBtn.hidden = scroll.scrollLeft < 10;
        rightBtn.hidden = scroll.scrollWidth - scroll.clientWidth - scroll.scrollLeft < 10;
      };
      toggleArrows();
      scroll.addEventListener('scroll', toggleArrows);
      leftBtn.addEventListener('click',()=> scroll.scrollBy({left: -240, behavior:'smooth'}));
      rightBtn.addEventListener('click',()=> scroll.scrollBy({left: 240, behavior:'smooth'}));

      // drag-to-scroll pour desktop
      let dragging=false, startX=0, startLeft=0, id=0;
      scroll.addEventListener('pointerdown', e=>{ dragging=true; startX=e.clientX; startLeft=scroll.scrollLeft; id=e.pointerId; scroll.setPointerCapture(id); });
      scroll.addEventListener('pointermove', e=>{ if(!dragging) return; scroll.scrollLeft = startLeft - (e.clientX - startX); });
      scroll.addEventListener('pointerup',   ()=>{ dragging=false; });
      scroll.addEventListener('pointercancel',()=>{ dragging=false; });
    }

    // Recherche clavier (Enter) -> page /recette/recherche/ (prévue plus tard)
    const search = document.getElementById('search-recette');
    const selectDiff = document.getElementById('filter-difficulte');
    search?.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && search.value.trim()) {
        const q = encodeURIComponent(search.value.trim());
        const d = selectDiff?.value ? '&d='+encodeURIComponent(selectDiff.value) : '';
        location.href = '<?= $RECETTE_WEBROOT ?>/recherche/?q='+q+d;
      }
    });
  })();
</script>
</body>
</html>
