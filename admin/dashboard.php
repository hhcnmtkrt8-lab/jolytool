<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

admin_session();
require_login();

$data = content();
$products = $data['products'] ?? [];
$news = $data['news'] ?? [];

$backups = glob(BACKUP_DIR . '/content-*.json') ?: [];
rsort($backups);
$lastBackup = $backups ? date('Y-m-d H:i', (int) filemtime($backups[0])) : '暂无';

$images = glob(ROOT . '/assets/img/products/*.jpg') ?: [];

$page_title = '仪表盘';
$active = 'dashboard';
?>
<?php require __DIR__ . '/partials/head.php'; ?>

<?php if (using_default_password()): ?>
  <div class="alert alert--err">您仍在使用初始密码 admin888，请立即前往 <a href="<?= e(admin_url('password.php')) ?>">修改密码</a>。</div>
<?php endif; ?>

<section class="grid-2">
  <div class="stat">
    <div class="stat__num"><?= count($products) ?></div>
    <div class="stat__label">个产品</div>
  </div>
  <div class="stat">
    <div class="stat__num"><?= count($news) ?></div>
    <div class="stat__label">条新闻</div>
  </div>
  <div class="stat">
    <div class="stat__num"><?= count($images) ?></div>
    <div class="stat__label">张产品图片</div>
  </div>
  <div class="stat">
    <div class="stat__num" style="font-size:16px;padding-top:8px"><?= e($lastBackup) ?></div>
    <div class="stat__label">最近一次自动备份</div>
  </div>
</section>

<section class="panel">
  <h2>日常怎么改</h2>
  <p class="panel__hint">所有修改点「保存」后立即生效，不需要发布或同步。</p>
  <table class="table">
    <tbody>
      <tr>
        <td style="width:120px"><strong>改文字</strong></td>
        <td>左侧「页面文字」，先选英文站或中文站，再选页面，修改后点保存。</td>
        <td style="width:110px"><a class="btn btn--ghost btn--sm" href="<?= e(admin_url('site.php')) ?>">进入</a></td>
      </tr>
      <tr>
        <td><strong>改产品</strong></td>
        <td>左侧「产品」，点击某个产品，可改名称、介绍、特点和规格表，并替换产品图。</td>
        <td><a class="btn btn--ghost btn--sm" href="<?= e(admin_url('products.php')) ?>">进入</a></td>
      </tr>
      <tr>
        <td><strong>发新闻</strong></td>
        <td>左侧「新闻」，可新增、修改、删除新闻。</td>
        <td><a class="btn btn--ghost btn--sm" href="<?= e(admin_url('news.php')) ?>">进入</a></td>
      </tr>
      <tr>
        <td><strong>换图片</strong></td>
        <td>左侧「图片」，可替换首页大图、材料图和每个产品的图片，支持 JPG / PNG。</td>
        <td><a class="btn btn--ghost btn--sm" href="<?= e(admin_url('images.php')) ?>">进入</a></td>
      </tr>
    </tbody>
  </table>
</section>

<section class="panel">
  <h2>最近备份</h2>
  <p class="panel__hint">每次保存前系统会自动备份一份内容文件，最新 40 份保留，可随时恢复。</p>
  <?php if (!$backups): ?>
    <p class="muted">还没有备份记录，第一次保存后就会出现。</p>
  <?php else: ?>
    <table class="table">
      <thead><tr><th>备份文件</th><th>时间</th><th>大小</th><th></th></tr></thead>
      <tbody>
      <?php foreach (array_slice($backups, 0, 8) as $file): ?>
        <tr>
          <td class="mono"><?= e(basename($file)) ?></td>
          <td><?= e(date('Y-m-d H:i', (int) filemtime($file))) ?></td>
          <td><?= e(number_format(filesize($file) / 1024, 1)) ?> KB</td>
          <td>
            <form method="post" action="<?= e(admin_url('restore.php')) ?>" onsubmit="return confirm('用这份备份覆盖当前内容？');">
              <?= csrf_field() ?>
              <input type="hidden" name="file" value="<?= e(basename($file)) ?>">
              <button class="btn btn--ghost btn--sm" type="submit">恢复</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/partials/foot.php'; ?>
