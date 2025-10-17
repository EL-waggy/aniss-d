<?php
// ---------- CONFIG (chemins sûrs OVH) ----------
$SITE_BASE         = '/';           // dossier racine du site (là où il y a /css, /img, etc.)
$RECETTE_WEBROOT   = '/recette';    // URL du dossier recettes
$RECETTE_FSROOT    = __DIR__;       // chemin disque du dossier /recette (fiable)

// ---------- HELPERS ----------
function listRecipeCategories(string $fsRoot): array {
  if (!is_dir($fsRoot)) return [];
  $cats = [];
  foreach (glob($fsRoot . '/*', GLOB_ONLYDIR) as $dir) {
    $slug = basename($dir);
    if ($slug === '' || $slug[0] === '_' || $slug === 'assets') continue;
    $count  = count(glob($dir . '/*.html')) + count(glob($dir . '/*.php'));
    $label  = ucfirst(str_replace(['-', '_'], ' ', $slug));
    $cats[] = ['slug' => $slug, 'label' => $label, 'count' => $count];
  }
  usort($cats, fn($a,$b) => strcmp($a['label'], $b['label']));
  return $cats;
}

function getActiveCategory(string $webroot): string {
  $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
  $segments = $path === '' ? [] : explode('/', $path);
  $root = trim($webroot, '/');
  $i = array_search($root, $segments, true);
  return ($i !== false && isset($segments[$i+1])) ? $segments[$i+1] : '';
}

function renderBreadcrumb(string $webroot, string $pageTitle = ''): string {
  $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
  $segments = $path === '' ? [] : explode('/', $path);
  $root = trim($webroot, '/');
  $out = [];
  $out[] = '<a href="/">Accueil</a>';
  $out[] = '<a href="' . htmlspecialchars($webroot) . '/">Recettes</a>';
  $i = array_search($root, $segments, true);
  if ($i !== false && isset($segments[$i+1]) && $segments[$i+1] !== '') {
    $catSlug  = $segments[$i+1];
    $catLabel = ucfirst(str_replace(['-', '_'], ' ', $catSlug));
    $out[] = '<a href="' . htmlspecialchars($webroot) . '/' . rawurlencode($catSlug) . '/">' . htmlspecialchars($catLabel) . '</a>';
  }
  if ($pageTitle) $out[] = htmlspecialchars($pageTitle);
  return implode(' / ', $out);
}

// ---------- DATA ----------
$categories = listRecipeCategories($RECETTE_FSROOT);
$activeCat  = getActiveCategory($RECETTE_WEBROOT);

// ---------- PAGE METADATA ----------
$pageTitle = '🍝 Spaghetti à la Carbonara';
$pageDescription = "Recette de cuisine par Aniss D.exe";
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recette - Aniss D.exe</title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
  <!-- chemins ABSOLUS sûrs -->
  <link rel="stylesheet" href="<?= $SITE_BASE ?>css/style.css">
  <link rel="icon" href="<?= $SITE_BASE ?>img/index/image-principale-1.png" sizes="any">
  <style>
    .subnav-recette { position: sticky; top: 0; z-index: 50; background:#fff; border-bottom:1px solid #eee; }
    .subnav-recette .subnav-inner { display:grid; grid-template-columns:auto 1fr auto auto; gap:.5rem; align-items:center; padding:.5rem 1rem; max-width:1200px; margin:0 auto; }
    .subnav-recette .subnav-scroll { display:flex; gap:.5rem; overflow-x:auto; scroll-snap-type:x proximity; padding:.25rem 0; }
    .subnav-recette .chip { flex:0 0 auto; scroll-snap-align:start; display:inline-flex; align-items:center; gap:.4rem; padding:.4rem .8rem; border:1px solid #e5e7eb; border-radius:999px; background:#fafafa; font-size:.95rem; line-height:1; text-decoration:none; color:#111; }
    .subnav-recette .chip[aria-selected="true"] { background:#111; color:#fff; border-color:#111; }
    .subnav-recette .chip .count { opacity:.6; font-variant-numeric: tabular-nums; }
    .subnav-recette .subnav-tools { display:flex; gap:.5rem; align-items:center; }
    .subnav-recette input[type="search"], .subnav-recette select { padding:.4rem .6rem; border:1px solid #e5e7eb; border-radius:.5rem; font-size:.95rem; }
    .subnav-recette .subnav-arrow { border:1px solid #e5e7eb; background:#fff; border-radius:.5rem; padding:.35rem .55rem; cursor:pointer; }
    @media (max-width:780px){ .subnav-recette .subnav-inner{grid-template-columns:auto 1fr auto} .subnav-recette .subnav-tools{grid-column:1 / -1} }
    .breadcrumb { max-width:1200px; margin:.5rem auto 0; padding:0 1rem; font-size:.9rem; color:#6b7280; }
    .breadcrumb a { color:inherit; text-decoration:underline; text-underline-offset:2px; }
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
    </div>

    <button class="subnav-arrow right" aria-label="Défiler vers la droite" hidden>▶</button>
  </div>
</nav>

<p class="breadcrumb"><?= renderBreadcrumb($RECETTE_WEBROOT, $pageTitle) ?></p>

<main>
  <article class="recette">
    <h2><?= htmlspecialchars($pageTitle) ?></h2>
    <img src="<?= $SITE_BASE ?>img/recette/pate_carbo.png"
         alt="Assiette de spaghetti à la carbonara"
         class="recette-img" width="300" height="300">

    <section class="introduction">
      <p>Une recette simple, rapide et délicieuse pour les amateurs de pâtes italiennes !</p>
    </section>

    <section class="ingredients">
      <h3>Ingrédients (pour 2 personnes)</h3>
      <ul>
        <li>200 g de spaghetti</li>
        <li>100 g de lardons</li>
        <li>2 jaunes d'œufs</li>
        <li>50 g de parmesan râpé</li>
        <li>Sel et poivre</li>
      </ul>
    </section>

    <section class="preparation">
      <h3>Préparation</h3>
      <ol>
        <li>Fais cuire les pâtes dans de l’eau bouillante salée.</li>
        <li>Fais revenir les lardons à la poêle jusqu’à ce qu’ils soient dorés.</li>
        <li>Dans un bol, mélange les jaunes d’œufs avec le parmesan et un peu de poivre.</li>
        <li>Égoutte les pâtes, garde un peu d’eau de cuisson.</li>
        <li>Mélange les pâtes, les lardons et la sauce dans la poêle hors du feu.</li>
        <li>Ajoute un peu d’eau de cuisson pour lier la sauce. Servez chaud !</li>
      </ol>
    </section>
  </article>

  <aside class="infos-recette">
    <h3>Informations</h3>
    <ul>
      <li><strong>Préparation :</strong> 15 min</li>
      <li><strong>Cuisson :</strong> 10 min</li>
      <li><strong>Difficulté :</strong> Facile</li>
      <li><strong>Catégorie :</strong> Plat principal</li>
    </ul>

    <h3>Autres recettes</h3>
    <ul>
      <li><a href="<?= $RECETTE_WEBROOT ?>/dessert/tiramisu.html">Tiramisu italien</a></li>
      <li><a href="<?= $RECETTE_WEBROOT ?>/salade/salade-cesar.html">Salade César</a></li>
      <li><a href="<?= $RECETTE_WEBROOT ?>/omelette/omelette.html">Omelette moelleuse</a></li>
    </ul>
  </aside>
</main>

<footer class="site-footer">
  <p>© <?= date('Y') ?> Aniss Dah. Tous droits réservés.</p>
  <nav aria-label="Navigation secondaire">
    <a href="<?= $SITE_BASE ?>Politique%20de%20confidentialit%C3%A9/" class="footer-link">Politique de confidentialité</a>
    <a href="<?= $SITE_BASE ?>contact/" class="footer-link">Contact</a>
  </nav>
</footer>

<script>
  (function(){
    const root = document.querySelector('.subnav-recette');
    if (!root) return;
    const scroll = root.querySelector('.subnav-scroll');
    const leftBtn = root.querySelector('.subnav-arrow.left');
    const rightBtn = root.querySelector('.subnav-arrow.right');

    function updateArrows(){
      const maxScroll = scroll.scrollWidth - scroll.clientWidth;
      leftBtn.hidden  = scroll.scrollLeft <= 4;
      rightBtn.hidden = scroll.scrollLeft >= maxScroll - 4;
    }
    if (scroll){
      updateArrows();
      scroll.addEventListener('scroll', updateArrows, {passive:true});
      leftBtn.addEventListener('click', ()=> scroll.scrollBy({left:-240, behavior:'smooth'}));
      rightBtn.addEventListener('click',()=> scroll.scrollBy({left: 240, behavior:'smooth'}));
      let dragging=false, startX=0, startLeft=0, id=0;
      scroll.addEventListener('pointerdown', e=>{ dragging=true; startX=e.clientX; startLeft=scroll.scrollLeft; id=e.pointerId; scroll.setPointerCapture(id); });
      scroll.addEventListener('pointermove', e=>{ if(!dragging) return; scroll.scrollLeft = startLeft - (e.clientX - startX); });
      scroll.addEventListener('pointerup',   ()=>{ dragging=false; });
      scroll.addEventListener('pointercancel',()=>{ dragging=false; });
    }

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
