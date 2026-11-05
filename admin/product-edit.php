<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
require __DIR__ . '/schema.php';
require __DIR__ . '/partials/fields.php';

admin_session();
require_login();

$slug = (string) ($_GET['slug'] ?? $_POST['slug'] ?? '');
$lang = (($_GET['lang'] ?? $_POST['lang'] ?? 'en') === 'cn') ? 'cn' : 'en';

$data = content();
$index = null;
foreach ($data['products'] as $i => $product) {
    if ($product['slug'] === $slug) {
        $index = $i;
        break;
    }
}
if ($index === null) {
    http_response_code(404);
    $page_title = '产品不存在';
    $active = 'products';
    require __DIR__ . '/partials/head.php';
    echo '<section class="panel"><p>没有找到这个产品。<a href="products.php">返回产品列表</a></p></section>';
    require __DIR__ . '/partials/foot.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $posted = [];
    foreach (($_POST['fields'] ?? []) as $item) {
        if (is_array($item) && isset($item['key'])) {
            $posted[(string) $item['key']] = $item;
        }
    }
    $target = $data['products'][$index][$lang];
    foreach (product_schema() as $field) {
        $key = (string) $field['key'];
        if (!array_key_exists($key, $posted)) {
            continue;
        }
        $raw = $posted[$key]['value'] ?? '';
        if ($key === 'spec') {
            $parsed = parse_spec_table((string) $raw);
            $target['cols'] = $parsed['cols'];
            $target['rows'] = $parsed['rows'];
        } else {
            $target[$key] = normalize_value($field, $raw);
        }
    }
    $data['products'][$index][$lang] = $target;

    if (save_all($data)) {
        flash('产品已保存。');
    } else {
        flash('保存失败，请检查 data 目录是否可写。', 'err');
    }
    redirect('product-edit.php?slug=' . urlencode($slug) . '&lang=' . $lang);
}

$product = $data['products'][$index];
$copy = $product[$lang];
$image = product_image($slug);

$page_title = '编辑产品 · ' . ($product['en']['name'] ?? $slug);
$active = 'products';

$specValue = spec_table_text(['cols' => $copy['cols'] ?? [], 'rows' => $copy['rows'] ?? []]);
?>
<?php require __DIR__ . '/partials/head.php'; ?>

<section class="panel">
  <h2>产品图片</h2>
  <p class="panel__hint">建议正方形照片，宽度 1200px 以上，JPG 或 PNG，系统会自动压缩。中英文站共用同一张图。</p>
  <div class="media-row">
    <?php if ($image): ?>
      <img class="thumb" style="width:120px;height:120px" src="<?= e(img_url($image)) ?>" alt="">
    <?php else: ?>
      <div class="thumb" style="width:120px;height:120px"></div>
    <?php endif; ?>
    <form method="post" action="upload.php" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="target" value="product">
      <input type="hidden" name="slug" value="<?= e($slug) ?>">
      <input type="hidden" name="lang" value="<?= e($lang) ?>">
      <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
      <button class="btn btn--sm" type="submit">上传新图</button>
    </form>
  </div>
</section>

<div class="tabs">
  <a href="product-edit.php?slug=<?= e(urlencode($slug)) ?>&lang=en" class="<?= $lang === 'en' ? 'is-active' : '' ?>">英文内容</a>
  <a href="product-edit.php?slug=<?= e(urlencode($slug)) ?>&lang=cn" class="<?= $lang === 'cn' ? 'is-active' : '' ?>">中文内容</a>
  <a href="products.php" style="margin-left:auto">← 返回产品列表</a>
</div>

<section class="panel" style="margin-top:0;border-top-left-radius:0">
  <form method="post" action="product-edit.php?slug=<?= e(urlencode($slug)) ?>&lang=<?= e($lang) ?>">
    <input type="hidden" name="slug" value="<?= e($slug) ?>">
    <input type="hidden" name="lang" value="<?= e($lang) ?>">

    <?php $i = 0; ?>
    <?php foreach (product_schema() as $field): ?>
      <?php
        $value = $field['key'] === 'spec' ? $specValue : ($copy[$field['key']] ?? '');
        echo field_control($i++, $field, $value);
      ?>
    <?php endforeach; ?>

    <div class="actions" style="margin:24px -24px -22px;border-radius:0 0 8px 8px">
      <?= csrf_field() ?>
      <button class="btn" type="submit">保存修改</button>
      <a class="btn btn--ghost" href="<?= e(product_url($slug)) ?>" target="_blank" rel="noopener">预览 ↗</a>
    </div>
  </form>
</section>

<?php require __DIR__ . '/partials/foot.php'; ?>
