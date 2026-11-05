<?php
require __DIR__ . '/includes/bootstrap.php';

$meta = [
    'title' => t('newsPageTitle') . ' | ' . c('company.' . lang() . '.shortName'),
    'desc' => t('newsPageAside'),
    'active' => 'news',
    'canonical' => SITE_URL . u('news.html'),
];

render_page('news', $meta);
