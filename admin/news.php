<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

admin_session();
require_login();

$news = content()['news'];
$page_title = '新闻';
$active = 'news';
?>
<?php require __DIR__ . '/partials/head.php'; ?>

<section class="panel">
  <h2>全部新闻（<?= count($news) ?> 条）</h2>
  <p class="panel__hint">新闻按日期从新到旧显示。新增后网址会自动生成，之后不要随意修改。</p>

  <p><a class="btn btn--sm" href="news-edit.php">＋ 新增新闻</a></p>

  <?php if (!$news): ?>
    <p class="muted">还没有新闻。</p>
  <?php else: ?>
    <table class="table">
      <thead>
        <tr>
          <th style="width:110px">日期</th>
          <th>英文标题</th>
          <th>中文标题</th>
          <th style="width:190px"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($news as $item): ?>
          <tr>
            <td class="mono"><?= e($item['date'] ?? '') ?></td>
            <td><strong><?= e($item['en']['title'] ?? '') ?></strong></td>
            <td><?= e($item['cn']['title'] ?? '') ?></td>
            <td class="row-actions">
              <a class="btn btn--ghost btn--sm" href="<?= e(admin_url('news-edit.php?slug=' . urlencode($item['slug']))) ?>">编辑</a>
              <form method="post" action="news-delete.php" onsubmit="return confirm('确定删除这条新闻？删除后无法恢复。');">
                <?= csrf_field() ?>
                <input type="hidden" name="slug" value="<?= e($item['slug']) ?>">
                <button class="btn btn--ghost btn--sm" type="submit">删除</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/partials/foot.php'; ?>
