<div class="shell">
  <div class="facts" data-reveal>
    <?php foreach (t('facts', []) as $fact): ?>
      <div class="facts__item">
        <span class="facts__value"><?= e($fact['value'] ?? '') ?></span>
        <span class="facts__label"><?= e($fact['label'] ?? '') ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</div>
