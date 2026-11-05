<?php
require __DIR__ . '/includes/bootstrap.php';

$meta = [
    'title' => c('company.' . lang() . '.name') . (is_cn() ? '｜关于我们' : ' | About'),
    'desc' => c('company.' . lang() . '.intro'),
    'active' => 'about',
    'canonical' => SITE_URL . u('about.html'),
];

render_page('about', $meta);
