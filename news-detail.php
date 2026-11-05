<?php
require __DIR__ . '/includes/bootstrap.php';

$slug = isset($_GET['slug']) ? (string) $_GET['slug'] : '';
$item = null;
foreach (content()['news'] as $candidate) {
    if ($candidate['slug'] === $slug) {
        $item = $candidate;
        break;
    }
}

if ($item === null) {
    http_response_code(404);
    $meta = ['title' => '404 | GOPEX', 'desc' => 'Not found', 'active' => 'news'];
    render_page('not-found', $meta);
    exit;
}

$data = $item[lang()];
$meta = [
    'title' => $data['title'] . ' | ' . c('company.' . lang() . '.shortName'),
    'desc' => $data['excerpt'] ?? '',
    'active' => 'news',
    'canonical' => SITE_URL . news_url($item['slug']),
];

render_page('news-detail', $meta, ['item' => $item]);
