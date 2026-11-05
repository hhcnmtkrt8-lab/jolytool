<?php
/** @var array $product */
$data = $product[lang()];
$img = product_image($product['slug']);
$index = 0;
foreach (content()['products'] as $i => $p) {
    if ($p['slug'] === $product['slug']) {
        $index = $i + 1;
        break;
    }
}
$related = [];
foreach (content()['products'] as $p) {
    if ($p['slug'] !== $product['slug'] && count($related) < 3) {
        $related[] = $p;
    }
}
?>
<section class="section--tight" style="padding-top:clamp(28px,4vw,48px)">
  <div class="shell">
    <nav class="crumbs" style="margin-bottom:clamp(24px,3vw,36px)" aria-label="Breadcrumb">
      <a href="<?= e(u()) ?>"><?= e(t('breadcrumbHome')) ?></a><span>/</span>
      <a href="<?= e(u('products.html')) ?>"><?= e(t('productsPageTitle')) ?></a><span>/</span>
      <span><?= e($data['name']) ?></span>
    </nav>
    <div class="pd">
      <div class="pd__media" data-reveal>
        <?php if ($img): ?>
          <img src="<?= e(img_url($img)) ?>" alt="<?= e($data['name']) ?>" width="800" height="800">
        <?php else: ?>
          <?= tile($index, $data['name']) ?>
        <?php endif; ?>
      </div>
      <div class="pd__body" data-reveal style="--reveal-delay:100ms">
        <span class="eyebrow"><?= e(t('productsEyebrow')) ?></span>
        <h1 class="display" style="font-size:clamp(2rem,3.6vw,3.2rem)"><?= e($data['name']) ?></h1>
        <?php if (!empty($data['keywords'])): ?>
          <div class="pd__keywords">
            <?php foreach ($data['keywords'] as $keyword): ?>
              <span class="tag"><?= e($keyword) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <p class="lede"><?= e($data['intro']) ?></p>

        <?php if (!empty($data['bullets'])): ?>
          <div>
            <h2 class="h3"><?= e($data['heading'] ?? '') ?></h2>
            <ul class="feature-list" style="margin-top:16px">
              <?php foreach ($data['bullets'] as $bullet): ?>
                <li><?= e($bullet) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if (!empty($data['uses'])): ?>
          <div>
            <h2 class="h3"><?= e($data['usesTitle'] ?? '') ?></h2>
            <ul class="feature-list" style="margin-top:16px">
              <?php foreach ($data['uses'] as $use): ?>
                <li><?= e($use) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if (!empty($data['rows'])): ?>
          <div>
            <div class="table-scroll">
              <table class="specs">
                <caption><?= e($data['specTitle'] ?? '') ?></caption>
                <thead>
                  <tr>
                    <?php foreach ($data['cols'] as $col): ?>
                      <th scope="col"><?= e($col) ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($data['rows'] as $row): ?>
                    <tr>
                      <?php foreach ($row as $cell): ?>
                        <td><?= e($cell) ?></td>
                      <?php endforeach; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <p class="muted" style="font-size:13.5px;margin-top:14px"><?= e(t('specNote')) ?></p>
          </div>
        <?php endif; ?>

        <div class="hero__actions">
          <a class="btn" href="<?= e(u('contact.html')) ?>"><?= e(t('quote')) ?><span class="arrow" aria-hidden="true">→</span></a>
          <a class="btn btn--ghost" href="<?= e(content()['contact']['whatsappHref']) ?>" target="_blank" rel="noopener">WhatsApp</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--paper2">
  <div class="shell">
    <div class="copy" data-reveal style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap">
      <h2 class="h2"><?= e(t('relatedTitle')) ?></h2>
      <a class="link-arrow" href="<?= e(u('products.html')) ?>"><?= e(t('backToProducts')) ?><span aria-hidden="true">→</span></a>
    </div>
    <div style="margin-top:clamp(24px,3vw,36px)">
      <?php $rows = $related; require ROOT . '/includes/partials/product-rows.php'; ?>
    </div>
  </div>
</section>

<?php require ROOT . '/includes/partials/cta.php'; ?>
