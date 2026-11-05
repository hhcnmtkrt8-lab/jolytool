<?php
$products = content()['products'];
?>
<div class="product-grid">
  <?php foreach ($products as $index => $product): ?>
    <?php
      $data = $product[lang()];
      $img = product_image($product['slug']);
    ?>
    <a class="product-tile" href="<?= e(product_url($product['slug'])) ?>" data-reveal>
      <span class="product-tile__media">
        <?php if ($img): ?>
          <img src="<?= e(img_url($img)) ?>" alt="<?= e($data['name']) ?>" width="800" height="800" loading="lazy">
        <?php else: ?>
          <?= tile($index + 1, $data['name']) ?>
        <?php endif; ?>
      </span>
      <span class="product-tile__no"><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
      <span class="product-tile__name"><?= e($data['name']) ?></span>
      <span class="product-tile__meta"><?= e($data['short'] ?? '') ?></span>
    </a>
  <?php endforeach; ?>
</div>
