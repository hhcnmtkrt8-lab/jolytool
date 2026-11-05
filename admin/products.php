<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

admin_session();
require_login();

$products = content()['products'];
$page_title = '产品';
$active = 'products';
?>
<?php require __DIR__ . '/partials/head.php'; ?>

<section class="panel">
  <h2>全部产品（<?= count($products) ?> 个）</h2>
  <p class="panel__hint">点击「编辑」修改文字与图片；产品的排序与网址后缀固定，如需调整请让技术人员处理。</p>

  <table class="table">
    <thead>
      <tr>
        <th style="width:70px">图片</th>
        <th>英文名称</th>
        <th>中文名称</th>
        <th style="width:220px">网址</th>
        <th style="width:80px"></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product): ?>
        <?php $img = product_image($product['slug']); ?>
        <tr>
          <td>
            <?php if ($img): ?>
              <img class="thumb" src="<?= e(img_url($img)) ?>" alt="">
            <?php else: ?>
              <span class="muted">无</span>
            <?php endif; ?>
          </td>
          <td><strong><?= e($product['en']['name'] ?? '') ?></strong></td>
          <td><?= e($product['cn']['name'] ?? '') ?></td>
          <td class="muted mono"><?= e('/products/' . $product['slug'] . '.html') ?></td>
          <td class="row-actions">
            <a class="btn btn--ghost btn--sm" href="<?= e(admin_url('product-edit.php?slug=' . urlencode($product['slug']))) ?>">编辑</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>

<?php require __DIR__ . '/partials/foot.php'; ?>
