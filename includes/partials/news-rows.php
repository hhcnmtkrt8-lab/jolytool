<div class="product-rows">
  <?php foreach (content()['news'] as $item): ?>
    <?php $data = $item[lang()]; ?>
    <a class="product-row product-row--news" href="<?= e(news_url($item['slug'])) ?>" data-reveal>
      <span class="product-row__no"><?= e($item['date']) ?></span>
      <span>
        <span class="product-row__name"><?= e($data['title']) ?></span>
        <span class="product-row__meta"><?= e($data['excerpt'] ?? '') ?></span>
      </span>
      <span class="product-row__arrow" aria-hidden="true">→</span>
    </a>
  <?php endforeach; ?>
</div>
