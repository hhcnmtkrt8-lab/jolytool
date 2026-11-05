<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
define('DATA_DIR', ROOT . '/data');
define('CONTENT_FILE', DATA_DIR . '/content.json');
define('SITE_URL', 'https://asiajoly.com');

$lang = (($_GET['lang'] ?? '') === 'cn') ? 'cn' : 'en';

require ROOT . '/includes/functions.php';

/* ------------------------------------------------------------------ */
/*  Data                                                               */
/* ------------------------------------------------------------------ */
function content(): array
{
    static $c = null;
    if ($c === null) {
        if (!is_file(CONTENT_FILE)) {
            http_response_code(500);
            exit('缺少 data/content.json');
        }
        $c = json_decode((string) file_get_contents(CONTENT_FILE), true);
        if (!is_array($c)) {
            http_response_code(500);
            exit('data/content.json 解析失败');
        }
    }
    return $c;
}

function save_content(array $data): bool
{
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        return false;
    }
    $tmp = CONTENT_FILE . '.tmp';
    if (file_put_contents($tmp, $json, LOCK_EX) === false) {
        return false;
    }
    return rename($tmp, CONTENT_FILE);
}

/* ------------------------------------------------------------------ */
/*  Language helpers                                                   */
/* ------------------------------------------------------------------ */
function lang(): string
{
    return $GLOBALS['lang'];
}

function is_cn(): bool
{
    return lang() === 'cn';
}

/** Deep get from content, e.g. c('site.' . lang() . '.heroTitle') */
function c(string $path, $default = '')
{
    $node = content();
    foreach (explode('.', $path) as $key) {
        if (!is_array($node) || !array_key_exists($key, $node)) {
            return $default;
        }
        $node = $node[$key];
    }
    return $node;
}

/** Site copy in the current language, e.g. t('heroTitle') */
function t(string $key, $default = '')
{
    return c('site.' . lang() . '.' . $key, $default);
}

/* ------------------------------------------------------------------ */
/*  URL helpers                                                        */
/* ------------------------------------------------------------------ */
function u(string $path = ''): string
{
    $path = ltrim($path, '/');
    if (is_cn()) {
        return '/cn/' . $path;
    }
    return '/' . $path;
}

function product_url(string $slug): string
{
    return u('products/' . $slug . '.html');
}

function news_url(string $slug): string
{
    return u('news/' . $slug . '.html');
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

function img_url(string $relPath): string
{
    $relPath = ltrim($relPath, '/');
    $file = ROOT . '/assets/' . $relPath;
    $v = is_file($file) ? (string) filemtime($file) : '1';
    return '/assets/' . $relPath . '?v=' . $v;
}

function product_image(string $slug): ?string
{
    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
        $rel = 'img/products/' . $slug . '.' . $ext;
        if (is_file(ROOT . '/assets/' . $rel)) {
            return $rel;
        }
    }
    return null;
}

/** Current path without the /cn prefix, used by the language switch. */
function alt_lang_url(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $path = preg_replace('#^/cn(?=/|$)#', '', $path) ?: '/';
    if (is_cn()) {
        return $path === '/' ? '/' : $path;
    }
    return '/cn' . ($path === '/' ? '/' : $path);
}

/* ------------------------------------------------------------------ */
/*  Rendering                                                          */
/* ------------------------------------------------------------------ */
function render_page(string $template, array $meta = [], array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    require ROOT . '/includes/header.php';
    require ROOT . '/includes/pages/' . $template . '.php';
    require ROOT . '/includes/footer.php';
}

function tile(int $no, string $name): string
{
    return '<div class="media-tile">'
        . '<span class="media-tile__no">' . e(str_pad((string) $no, 2, '0', STR_PAD_LEFT)) . '</span>'
        . '<span class="media-tile__name">' . e($name) . '</span>'
        . '</div>';
}
