<?php
$capabilities = t('capabilities', []);
?>
<section class="section section--tight">
  <div class="shell">
    <div class="copy" data-reveal>
      <span class="eyebrow"><?= is_cn() ? '制造' : 'Manufacturing' ?></span>
      <h2 class="h2"><?= e(t('capabilitiesTitle')) ?></h2>
    </div>
    <div class="capabilities" style="margin-top: clamp(28px, 4vw, 48px)">
      <?php foreach ($capabilities as $item): ?>
        <article class="capability" data-reveal>
          <span class="capability__no"><?= e($item['no'] ?? '') ?></span>
          <h3 class="h3"><?= e($item['title'] ?? '') ?></h3>
          <p><?= e($item['text'] ?? '') ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
