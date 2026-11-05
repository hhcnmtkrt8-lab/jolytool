<?php
require __DIR__ . '/includes/bootstrap.php';

$meta = [
    'title' => (is_cn() ? '产品中心' : 'Products') . ' | ' . c('company.' . lang() . '.shortName'),
    'desc' => t('productsPageAside'),
    'active' => 'products',
    'canonical' => SITE_URL . u('products.html'),
];

render_page('products', $meta);
