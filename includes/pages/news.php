<section class="page-head">
  <div class="shell page-head__inner">
    <div data-reveal>
      <nav class="crumbs" aria-label="Breadcrumb">
        <a href="<?= e(u()) ?>"><?= e(t('breadcrumbHome')) ?></a><span>/</span>
        <span><?= e(t('newsPageTitle')) ?></span>
      </nav>
      <h1 class="page-head__title"><?= e(t('newsPageTitle')) ?></h1>
    </div>
    <p class="page-head__aside" data-reveal><?= e(t('newsPageAside')) ?></p>
  </div>
</section>

<section class="section">
  <div class="shell">
    <?php require ROOT . '/includes/partials/news-rows.php'; ?>
  </div>
</section>

<?php require ROOT . '/includes/partials/cta.php'; ?>
