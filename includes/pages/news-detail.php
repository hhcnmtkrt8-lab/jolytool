<?php
/** @var array $item */
$data = $item[lang()];
?>
<section class="section">
  <div class="shell">
    <nav class="crumbs" style="margin-bottom:clamp(24px,3vw,36px)" aria-label="Breadcrumb">
      <a href="<?= e(u()) ?>"><?= e(t('breadcrumbHome')) ?></a><span>/</span>
      <a href="<?= e(u('news.html')) ?>"><?= e(t('newsPageTitle')) ?></a><span>/</span>
      <span><?= e($data['title']) ?></span>
    </nav>
    <article class="article" data-reveal>
      <div class="article__meta"><span><?= e(t('publishLabel')) ?></span><span><?= e($item['date']) ?></span></div>
      <h1 class="display" style="font-size:clamp(1.9rem,3.4vw,3rem);margin-top:18px"><?= e($data['title']) ?></h1>
      <div class="article__body" style="margin-top:28px">
        <?php foreach ($data['body'] as $paragraph): ?>
          <p><?= e($paragraph) ?></p>
        <?php endforeach; ?>
      </div>
      <p style="margin-top:36px"><a class="link-arrow" href="<?= e(u('news.html')) ?>"><?= e(t('newsAll')) ?><span aria-hidden="true">→</span></a></p>
    </article>
  </div>
</section>

<?php require ROOT . '/includes/partials/cta.php'; ?>
