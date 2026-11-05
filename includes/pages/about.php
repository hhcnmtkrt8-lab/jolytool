<?php
$intro = c('company.' . lang());
$locationTile = is_cn() ? content()['contact']['addressCN'] : 'Motou Industrial Park, Rugao';
?>
<section class="page-head">
  <div class="shell page-head__inner">
    <div data-reveal>
      <nav class="crumbs" aria-label="Breadcrumb">
        <a href="<?= e(u()) ?>"><?= e(t('breadcrumbHome')) ?></a><span>/</span>
        <span><?= e(is_cn() ? '关于我们' : 'About') ?></span>
      </nav>
      <h1 class="page-head__title"><?= e(t('aboutTitle')) ?></h1>
    </div>
    <p class="page-head__aside" data-reveal><?= e(t('aboutAside')) ?></p>
  </div>
</section>

<section class="section">
  <div class="shell split">
    <div class="copy" data-reveal>
      <span class="eyebrow"><?= e(t('introEyebrow')) ?></span>
      <h2 class="h2" style="margin-top:20px"><?= e(c('company.' . lang() . '.shortName')) ?></h2>
      <p class="lede"><?= e($intro['intro']) ?></p>
      <p style="color:var(--ink-2)"><?= e($intro['intro2']) ?></p>
      <p style="color:var(--ink-2)"><?= e($intro['intro3']) ?></p>
    </div>
    <div data-reveal style="--reveal-delay:100ms">
      <?= tile(1, is_cn() ? '自 2004 年 · 江苏如皋' : 'Since 2004 · Rugao, Jiangsu') ?>
    </div>
  </div>
</section>

<section class="section--tight" style="padding-block:0">
  <?php require ROOT . '/includes/partials/facts.php'; ?>
</section>

<?php require ROOT . '/includes/partials/capabilities.php'; ?>

<section class="section section--paper2">
  <div class="shell split">
    <div class="copy" data-reveal>
      <span class="eyebrow"><?= e(t('productsEyebrow')) ?></span>
      <h2 class="h2" style="margin-top:20px"><?= e(t('productsTitle')) ?></h2>
      <p class="lede" style="margin-top:18px"><?= e(t('productsLede')) ?></p>
      <p style="margin-top:26px"><a class="btn" href="<?= e(u('products.html')) ?>"><?= e(t('allProducts')) ?><span class="arrow" aria-hidden="true">→</span></a></p>
    </div>
    <?php
      $rows = array_slice(content()['products'], 0, 5);
      require ROOT . '/includes/partials/product-rows.php';
    ?>
  </div>
</section>

<?php require ROOT . '/includes/partials/macro.php'; ?>
<?php require ROOT . '/includes/partials/quality.php'; ?>

<section class="section section--tight">
  <div class="shell split">
    <div class="copy" data-reveal>
      <span class="eyebrow"><?= e(t('footerContact')) ?></span>
      <h2 class="h2" style="margin-top:20px"><?= e(is_cn() ? '如皋磨头工业园区' : 'Motou Industrial Park, Rugao') ?></h2>
      <p style="color:var(--ink-2)"><?= e(is_cn() ? $intro['intro3'] : 'Two and a half hours from the port of Shanghai.') ?></p>
      <p><a class="link-arrow" href="<?= e(u('contact.html')) ?>"><?= e(is_cn() ? '获取联系方式' : 'Contact details') ?><span aria-hidden="true">→</span></a></p>
    </div>
    <div data-reveal style="--reveal-delay:100ms">
      <?= tile(2, $locationTile) ?>
    </div>
  </div>
</section>

<?php require ROOT . '/includes/partials/cta.php'; ?>
