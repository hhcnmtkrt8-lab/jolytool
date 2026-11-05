<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
require __DIR__ . '/schema.php';
require __DIR__ . '/partials/fields.php';

admin_session();
require_login();

$schema = site_schema();
$lang = (($_GET['lang'] ?? 'en') === 'cn') ? 'cn' : 'en';
$page = (string) ($_GET['p'] ?? 'home');
if (!isset($schema[$page])) {
    $page = 'home';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $data = content();
    $posted = [];
    foreach (($_POST['fields'] ?? []) as $item) {
        if (is_array($item) && isset($item['key'])) {
            $posted[(string) $item['key']] = $item;
        }
    }

    foreach ($schema[$page]['groups'] as $group) {
        $root = $group['root'] ?? 'site';
        if (!isset($data[$root][$lang])) {
            $data[$root][$lang] = [];
        }
        $target = &$data[$root][$lang];
        foreach ($group['fields'] as $field) {
            $key = (string) $field['key'];
            if (!array_key_exists($key, $posted)) {
                continue;
            }
            $raw = $posted[$key]['rows'] ?? ($posted[$key]['value'] ?? '');
            $value = normalize_value($field, $raw);
            if ($field['type'] === 'table') {
                $target['cols'] = $value['cols'] ?? [];
                $target['rows'] = $value['rows'] ?? [];
            } else {
                deep_set($target, explode('.', $key), $value);
            }
        }
        unset($target);
    }

    if (save_all($data)) {
        flash('已保存。前台刷新即可看到更新。');
    } else {
        flash('保存失败，请检查 data 目录是否可写。', 'err');
    }
    redirect('site.php?p=' . urlencode($page) . '&lang=' . $lang);
}

$content = content();
$page_title = '页面文字 · ' . $schema[$page]['label'];
$active = 'site';

$query = static fn (string $p, string $l): string => 'site.php?p=' . urlencode($p) . '&lang=' . urlencode($l);
?>
<?php require __DIR__ . '/partials/head.php'; ?>

<div class="tabs">
  <a href="<?= e($query($page, 'en')) ?>" class="<?= $lang === 'en' ? 'is-active' : '' ?>">英文站</a>
  <a href="<?= e($query($page, 'cn')) ?>" class="<?= $lang === 'cn' ? 'is-active' : '' ?>">中文站</a>
</div>

<section class="panel" style="margin-top:0;border-top-left-radius:0">
  <div class="tabs" style="margin:0 0 22px">
    <?php foreach ($schema as $key => $definition): ?>
      <a href="<?= e($query($key, $lang)) ?>" class="<?= $page === $key ? 'is-active' : '' ?>"><?= e($definition['label']) ?></a>
    <?php endforeach; ?>
  </div>

  <form method="post" action="<?= e($query($page, $lang)) ?>">
    <?php $i = 0; ?>
    <?php foreach ($schema[$page]['groups'] as $group): ?>
      <?php
        $root = $group['root'] ?? 'site';
        $scope = $content[$root][$lang] ?? [];
      ?>
      <div class="panel" style="padding:20px 20px 6px;background:#fbfaf7">
        <h2><?= e($group['title']) ?></h2>
        <?php if (!empty($group['desc'])): ?>
          <p class="panel__hint"><?= e($group['desc']) ?></p>
        <?php endif; ?>
        <?php foreach ($group['fields'] as $field): ?>
          <?php
            $value = deep_get($scope, (string) $field['key']);
            echo field_control($i++, $field, $value);
          ?>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>

    <div class="actions" style="margin:24px -24px -22px;border-radius:0 0 8px 8px">
      <?= csrf_field() ?>
      <button class="btn" type="submit">保存修改</button>
      <a class="btn btn--ghost" href="/<?= $lang === 'cn' ? 'cn/' : '' ?>" target="_blank" rel="noopener">预览<?= $lang === 'cn' ? '中文站' : '英文站' ?> ↗</a>
    </div>
  </form>
</section>

<?php require __DIR__ . '/partials/foot.php'; ?>
