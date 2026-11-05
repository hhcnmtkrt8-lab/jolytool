<?php
$intro = c('company.' . lang());
$heroImage = c('images.hero', 'img/mesh-hero.jpg');
?>
<section class="hero">
  <div class="hero__media">
    <img src="<?= e(img_url($heroImage)) ?>" alt="<?= e(is_cn() ? '玻纤网格胶带成品卷' : 'Fiberglass mesh tape rolls') ?>"
         width="2400" height="1350" fetchpriority="high">
  </div>
  <div class="shell hero__inner">
    <div class="hero__copy">
      <span class="eyebrow hero__eyebrow"><?= e(t('heroEyebrow')) ?></span>
      <h1 class="hero__title"><?= e(t('heroTitle')) ?></h1>
      <p class="hero__lede"><?= e(t('heroLede')) ?></p>
      <div class="hero__actions">
        <a class="btn" href="<?= e(u('products.html')) ?>"><?= e(t('heroCta')) ?><span class="arrow" aria-hidden="true">→</span></a>
        <a class="btn btn--ghost" href="<?= e(u('contact.html')) ?>"><?= e(t('heroCta2')) ?></a>
      </div>
    </div>
    <div class="hero__spec">
      <?php foreach (t('heroSpec', []) as $spec): ?>
        <div><strong><?= e($spec['t']) ?></strong><em><?= e($spec['s']) ?></em></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section--tight" style="padding-block:0">
  <?php require ROOT . '/includes/partials/facts.php'; ?>
</section>

<section class="section">
  <div class="shell split">
    <div class="copy" data-reveal>
      <span class="eyebrow"><?= e(t('introEyebrow')) ?></span>
      <h2 class="h2" style="margin-top:20px"><?= e(t('introTitle')) ?></h2>
      <p class="lede"><?= e($intro['intro']) ?></p>
      <p style="color:var(--ink-2)"><?= e($intro['intro2']) ?></p>
      <p style="color:var(--ink-2)"><?= e($intro['intro3']) ?></p>
      <p><a class="link-arrow" href="<?= e(u('about.html')) ?>"><?= e(is_cn() ? '了解公司详情' : 'More about the company') ?><span aria-hidden="true">→</span></a></p>
    </div>
    <div data-reveal style="--reveal-delay:100ms">
      <?= tile(1, is_cn() ? '玻纤网格布' : 'Fiberglass mesh') ?>
    </div>
  </div>
</section>

<?php require ROOT . '/includes/partials/capabilities.php'; ?>

<section class="section">
  <div class="shell products-layout">
    <div class="products-intro" data-reveal>
      <span class="eyebrow"><?= e(t('productsEyebrow')) ?></span>
      <h2 class="h2" style="margin-top:20px"><?= e(t('productsTitle')) ?></h2>
      <p class="lede" style="margin-top:18px"><?= e(t('productsLede')) ?></p>
      <p style="margin-top:26px"><a class="link-arrow" href="<?= e(u('products.html')) ?>"><?= e(t('allProducts')) ?><span aria-hidden="true">→</span></a></p>
    </div>
    <?php require ROOT . '/includes/partials/product-rows.php'; ?>
  </div>
</section>

<?php require ROOT . '/includes/partials/macro.php'; ?>
<?php require ROOT . '/includes/partials/quality.php'; ?>

<section class="section section--tight">
  <div class="shell">
    <div class="copy" data-reveal style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;justify-content:space-between">
      <div>
        <span class="eyebrow"><?= e(t('newsEyebrow')) ?></span>
        <h2 class="h2" style="margin-top:18px"><?= e(t('newsTitle')) ?></h2>
      </div>
      <a class="link-arrow" href="<?= e(u('news.html')) ?>"><?= e(t('newsAll')) ?><span aria-hidden="true">→</span></a>
    </div>
    <div style="margin-top:clamp(28px,4vw,44px)">
      <?php require ROOT . '/includes/partials/news-rows.php'; ?>
    </div>
  </div>
</section>

<?php require ROOT . '/includes/partials/cta.php'; ?>
