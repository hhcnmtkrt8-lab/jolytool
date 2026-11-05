<?php
/** @var array $rows */
$rows = $rows ?? content()['products'];
?>
<div class="product-rows">
  <?php foreach ($rows as $index => $product): ?>
    <?php
      $data = $product[lang()];
      $img = product_image($product['slug']);
    ?>
    <a class="product-row product-row--thumb" href="<?= e(product_url($product['slug'])) ?>" data-reveal>
      <span class="product-row__thumb">
        <?php if ($img): ?>
          <img src="<?= e(img_url($img)) ?>" alt="" width="56" height="56" loading="lazy">
        <?php endif; ?>
      </span>
      <span class="product-row__no"><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
      <span>
        <span class="product-row__name"><?= e($data['name']) ?></span>
        <span class="product-row__meta"><?= e($data['short'] ?? '') ?></span>
      </span>
      <span class="product-row__arrow" aria-hidden="true">→</span>
    </a>
  <?php endforeach; ?>
</div>
