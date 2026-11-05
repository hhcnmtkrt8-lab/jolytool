<?php
/** @var array $meta */
$navItems = t('nav', []);
$canvas = $meta['canonical'] ?? (SITE_URL . (is_cn() ? '/cn/' : '/'));
?>
<!DOCTYPE html>
<html lang="<?= is_cn() ? 'zh-CN' : 'en' ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($meta['title'] ?? 'GOPEX') ?></title>
<meta name="description" content="<?= e($meta['desc'] ?? '') ?>">
<?php if (!empty($meta['keywords'])): ?>
<meta name="keywords" content="<?= e($meta['keywords']) ?>">
<?php endif; ?>
<link rel="canonical" href="<?= e($canvas) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($meta['title'] ?? 'GOPEX') ?>">
<meta property="og:description" content="<?= e($meta['desc'] ?? '') ?>">
<meta property="og:image" content="<?= e($meta['image'] ?? (SITE_URL . asset(c('images.hero', 'img/mesh-hero.jpg')))) ?>">
<meta property="og:url" content="<?= e($canvas) ?>">
<meta name="theme-color" content="#15171a">
<link rel="icon" href="<?= e(asset('img/favicon.svg')) ?>" type="image/svg+xml">
<link rel="stylesheet" href="<?= e(img_url('css/main.css')) ?>">
</head>
<body>
<header class="header">
  <div class="shell header__inner">
    <a class="brand" href="<?= e(u()) ?>" aria-label="<?= e(c('company.' . lang() . '.name')) ?>">
      <span class="brand__mark">GOPEX</span>
      <span class="brand__sub"><?= e(t('brandSub')) ?></span>
    </a>
    <nav class="nav" id="nav" aria-label="<?= is_cn() ? '主导航' : 'Main' ?>">
      <ul class="nav__list">
        <?php foreach ($navItems as $navItem): ?>
          <?php $current = ($meta['active'] ?? '') === ($navItem['key'] ?? ''); ?>
          <li>
            <a class="nav__link" href="<?= e(u(($navItem['href'] ?? 'index') === 'index' ? '' : ($navItem['href'] ?? '') . '.html')) ?>"
               <?= $current ? 'aria-current="page"' : '' ?>><?= e($navItem['label'] ?? '') ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <div class="header__actions">
      <a class="lang" href="<?= e(alt_lang_url()) ?>" hreflang="<?= is_cn() ? 'en' : 'zh-CN' ?>"><?= e(t('langLabel')) ?></a>
      <a class="btn" href="<?= e(u('contact.html')) ?>"><?= e(t('quote')) ?><span class="arrow" aria-hidden="true">→</span></a>
      <button class="nav-toggle" type="button" aria-controls="nav" aria-expanded="false" aria-label="<?= is_cn() ? '打开菜单' : 'Open menu' ?>"><span></span></button>
    </div>
  </div>
</header>
<main>
