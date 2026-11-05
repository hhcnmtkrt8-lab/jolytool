<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
require __DIR__ . '/partials/fields.php';

admin_session();
require_login();

$data = content();
$slug = (string) ($_GET['slug'] ?? $_POST['slug'] ?? '');
$isNew = $slug === '';
$lang = (($_GET['lang'] ?? 'en') === 'cn') ? 'cn' : 'en';

$index = null;
foreach ($data['news'] as $i => $item) {
    if ($item['slug'] === $slug) {
        $index = $i;
        break;
    }
}
if (!$isNew && $index === null) {
    http_response_code(404);
    $page_title = '新闻不存在';
    $active = 'news';
    require __DIR__ . '/partials/head.php';
    echo '<section class="panel"><p>没有找到这条新闻。<a href="news.php">返回新闻列表</a></p></section>';
    require __DIR__ . '/partials/foot.php';
    exit;
}

$newsSchema = [
    ['key' => 'title', 'label' => '标题', 'type' => 'text'],
    ['key' => 'excerpt', 'label' => '摘要', 'type' => 'textarea', 'rows' => 3],
    ['key' => 'body', 'label' => '正文（每行一段）', 'type' => 'lines', 'rows' => 9],
];

/** Read one language block out of the posted fields. */
function collect_lang(array $posted, array $schema): array
{
    $map = [];
    foreach ($posted as $item) {
        if (is_array($item) && isset($item['key'])) {
            $map[(string) $item['key']] = $item;
        }
    }
    $out = [];
    foreach ($schema as $field) {
        $key = (string) $field['key'];
        if (array_key_exists($key, $map)) {
            $out[$key] = normalize_value($field, $map[$key]['value'] ?? '');
        }
    }
    return $out;
}

function make_slug(string $source, array $existing): string
{
    $base = strtolower(trim($source));
    $base = preg_replace('/[^a-z0-9]+/', '-', $base) ?? '';
    $base = trim($base, '-');
    if ($base === '') {
        $base = 'news-' . date('Ymd') . '-' . substr(bin2hex(random_bytes(3)), 0, 4);
    }
    $base = substr($base, 0, 60);
    $candidate = $base;
    $n = 2;
    while (in_array($candidate, $existing, true)) {
        $candidate = $base . '-' . $n++;
    }
    return $candidate;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $date = post('date');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $date = date('Y-m-d');
    }

    if ($isNew) {
        $en = collect_lang((array) ($_POST['fields']['en'] ?? []), $newsSchema);
        $cn = collect_lang((array) ($_POST['fields']['cn'] ?? []), $newsSchema);
        if (($en['title'] ?? '') === '' && ($cn['title'] ?? '') === '') {
            flash('请至少填写一个语言的标题。', 'err');
            redirect('news-edit.php');
        }
        $existing = array_map(static fn ($n) => $n['slug'], $data['news']);
        $newSlug = make_slug((string) ($en['title'] ?? $cn['title']), $existing);

        $data['news'][] = ['slug' => $newSlug, 'date' => $date, 'en' => $en, 'cn' => $cn];
        usort($data['news'], static fn ($a, $b) => strcmp((string) $b['date'], (string) $a['date']));

        $saved = save_all($data);
        flash($saved ? '新闻已发布。' : '保存失败，请检查 data 目录是否可写。', $saved ? 'ok' : 'err');
        redirect('news.php');
    }

    $copy = $data['news'][$index][$lang] ?? [];
    $copy = array_merge($copy, collect_lang((array) ($_POST['fields'][$lang] ?? []), $newsSchema));
    $data['news'][$index][$lang] = $copy;
    $data['news'][$index]['date'] = $date;
    usort($data['news'], static fn ($a, $b) => strcmp((string) $b['date'], (string) $a['date']));

    $saved = save_all($data);
    flash($saved ? '新闻已保存。' : '保存失败，请检查 data 目录是否可写。', $saved ? 'ok' : 'err');
    redirect('news-edit.php?slug=' . urlencode($slug) . '&lang=' . $lang);
}

$item = $isNew
    ? ['slug' => '', 'date' => date('Y-m-d'), 'en' => [], 'cn' => []]
    : $data['news'][$index];

$page_title = $isNew ? '新增新闻' : '编辑新闻';
$active = 'news';
?>
<?php require __DIR__ . '/partials/head.php'; ?>

<div class="tabs">
  <?php if (!$isNew): ?>
    <a href="news-edit.php?slug=<?= e(urlencode($slug)) ?>&lang=en" class="<?= $lang === 'en' ? 'is-active' : '' ?>">英文内容</a>
    <a href="news-edit.php?slug=<?= e(urlencode($slug)) ?>&lang=cn" class="<?= $lang === 'cn' ? 'is-active' : '' ?>">中文内容</a>
  <?php else: ?>
    <a href="#" class="is-active">填写中英文内容</a>
  <?php endif; ?>
  <a href="news.php" style="margin-left:auto">← 返回新闻列表</a>
</div>

<section class="panel" style="margin-top:0;border-top-left-radius:0">
  <form method="post" action="<?= $isNew ? 'news-edit.php' : 'news-edit.php?slug=' . e(urlencode($slug)) . '&lang=' . e($lang) ?>">
    <input type="hidden" name="slug" value="<?= e($slug) ?>">

    <div class="field" style="max-width:260px">
      <label for="date">发布日期</label>
      <input id="date" type="date" name="date" value="<?= e($item['date']) ?>" style="width:100%;padding:9px 11px;border:1px solid #d8d3c9;border-radius:5px;font:inherit">
    </div>

    <?php if ($isNew): ?>
      <div class="panel" style="background:#fbfaf7">
        <h2>英文内容</h2>
        <?php $i = 0; foreach ($newsSchema as $field) { echo field_control($i, $field, '', 'fields[en]'); $i++; } ?>
      </div>
      <div class="panel" style="background:#fbfaf7">
        <h2>中文内容</h2>
        <?php $i = 0; foreach ($newsSchema as $field) { echo field_control($i, $field, '', 'fields[cn]'); $i++; } ?>
      </div>
    <?php else: ?>
      <?php $i = 0; foreach ($newsSchema as $field) { echo field_control($i, $field, $item[$lang][$field['key']] ?? '', 'fields[' . $lang . ']'); $i++; } ?>
    <?php endif; ?>

    <div class="actions" style="margin:24px -24px -22px;border-radius:0 0 8px 8px">
      <?= csrf_field() ?>
      <button class="btn" type="submit"><?= $isNew ? '发布新闻' : '保存修改' ?></button>
      <?php if (!$isNew): ?>
        <a class="btn btn--ghost" href="<?= e(news_url($slug)) ?>" target="_blank" rel="noopener">预览 ↗</a>
      <?php endif; ?>
    </div>
  </form>
</section>

<?php require __DIR__ . '/partials/foot.php'; ?>
