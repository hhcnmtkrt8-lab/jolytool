<?php
require __DIR__ . '/includes/bootstrap.php';

$slug = isset($_GET['slug']) ? (string) $_GET['slug'] : '';
$product = null;
foreach (content()['products'] as $candidate) {
    if ($candidate['slug'] === $slug) {
        $product = $candidate;
        break;
    }
}

if ($product === null) {
    http_response_code(404);
    $meta = ['title' => '404 | GOPEX', 'desc' => 'Not found', 'active' => 'products'];
    render_page('not-found', $meta);
    exit;
}

$data = $product[lang()];
$meta = [
    'title' => $data['name'] . ' | ' . c('company.' . lang() . '.shortName'),
    'desc' => mb_substr($data['intro'], 0, 150),
    'keywords' => implode(', ', $data['keywords'] ?? []),
    'active' => 'products',
    'canonical' => SITE_URL . product_url($product['slug']),
    'image' => SITE_URL . asset(product_image($product['slug']) ?? c('images.hero')),
];

render_page('product', $meta, ['product' => $product]);
