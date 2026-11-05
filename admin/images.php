<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

admin_session();
require_login();

$data = content();
$hero = (string) deep_get($data, 'images.hero', 'img/mesh-hero.jpg');
$macro = (string) deep_get($data, 'images.macro', 'img/mesh-macro.jpg');
$products = $data['products'];

$page_title = '图片';
$active = 'images';
?>
<?php require __DIR__ . '/partials/head.php'; ?>

<section class="panel">
  <h2>首页主图</h2>
  <p class="panel__hint">建议横图，宽度 2000px 以上。中英文站共用。</p>
  <div class="media-row">
    <img class="thumb thumb--wide" src="<?= e(img_url($hero)) ?>" alt="">
    <div class="media-row__info">
      <div class="media-row__title">顶部大图</div>
      <div class="media-row__meta"><?= e($hero) ?></div>
    </div>
    <form method="post" action="upload.php" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="target" value="hero">
      <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
      <button class="btn btn--sm" type="submit">上传替换</button>
    </form>
  </div>

  <div class="media-row">
    <img class="thumb thumb--wide" src="<?= e(img_url($macro)) ?>" alt="">
    <div class="media-row__info">
      <div class="media-row__title">材料特写图</div>
      <div class="media-row__meta"><?= e($macro) ?></div>
    </div>
    <form method="post" action="upload.php" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="target" value="macro">
      <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
      <button class="btn btn--sm" type="submit">上传替换</button>
    </form>
  </div>
</section>

<section class="panel">
  <h2>产品图片</h2>
  <p class="panel__hint">建议正方形照片，宽度 1200px 左右，背景干净。</p>

  <?php foreach ($products as $product): ?>
    <?php $img = product_image($product['slug']); ?>
    <div class="media-row">
      <?php if ($img): ?>
        <img class="thumb" src="<?= e(img_url($img)) ?>" alt="">
      <?php else: ?>
        <div class="thumb"></div>
      <?php endif; ?>
      <div class="media-row__info">
        <div class="media-row__title"><?= e($product['cn']['name'] ?? '') ?> <span class="muted">/ <?= e($product['en']['name'] ?? '') ?></span></div>
        <div class="media-row__meta"><?= e($img ?: '尚未设置图片') ?></div>
      </div>
      <form method="post" action="upload.php" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="target" value="product">
        <input type="hidden" name="slug" value="<?= e($product['slug']) ?>">
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
        <button class="btn btn--sm" type="submit">上传替换</button>
      </form>
    </div>
  <?php endforeach; ?>
</section>

<?php require __DIR__ . '/partials/foot.php'; ?>
