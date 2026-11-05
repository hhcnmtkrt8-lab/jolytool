<?php
$meta = t('macroMeta', []);
?>
<section class="macro">
  <img src="<?= e(img_url(c('images.macro', 'img/mesh-macro.jpg'))) ?>"
       alt="" width="1200" height="1200" loading="lazy">
  <div class="shell macro__inner">
    <div class="macro__text" data-reveal>
      <span class="eyebrow"><?= e(t('macroEyebrow')) ?></span>
      <p><?= e(t('macroText')) ?></p>
    </div>
    <div class="macro__meta" data-reveal>
      <?php foreach ($meta as $line): ?>
        <?= e($line) ?><br>
      <?php endforeach; ?>
    </div>
  </div>
</section>
