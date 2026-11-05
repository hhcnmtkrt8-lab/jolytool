<?php
require __DIR__ . '/includes/bootstrap.php';

$meta = [
    'title' => is_cn()
        ? '南通高朋建材有限公司｜玻璃纤维自粘胶带·网格布·金属护角带'
        : 'GOPEX | Fiberglass Mesh Tape & Alkali-Resistant Mesh Manufacturer',
    'desc' => c('company.' . lang() . '.intro'),
    'keywords' => is_cn()
        ? '玻璃纤维自粘胶带,玻纤网格布,金属护角带,接缝纸带,金属补墙板,南通高朋建材'
        : 'fiberglass mesh tape, alkali-resistant fiberglass mesh, metal corner tape, paper tape, wall patch',
    'active' => 'home',
    'canonical' => SITE_URL . u(),
];

render_page('home', $meta);
