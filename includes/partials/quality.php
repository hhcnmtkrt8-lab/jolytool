<section class="section section--dark">
  <div class="shell quality">
    <div class="copy" data-reveal>
      <span class="eyebrow"><?= e(t('qualityEyebrow')) ?></span>
      <h2 class="h2" style="margin-top:20px"><?= e(t('qualityTitle')) ?></h2>
      <p class="lede" style="margin-top:20px"><?= e(t('qualityLede')) ?></p>
    </div>
    <div data-reveal style="--reveal-delay:120ms">
      <dl class="quality__list" style="margin:0">
        <?php foreach (t('qualityItems', []) as $item): ?>
          <div class="quality__item">
            <dt><?= e($item['k'] ?? '') ?></dt>
            <dd><?= e($item['v'] ?? '') ?></dd>
          </div>
        <?php endforeach; ?>
      </dl>
    </div>
  </div>
</section>
