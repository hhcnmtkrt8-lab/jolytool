<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

define('ADMIN_DIR', __DIR__);
define('CRED_FILE', DATA_DIR . '/admin.json');
define('BACKUP_DIR', DATA_DIR . '/backups');
define('DEFAULT_PASSWORD', 'admin888');

/* ------------------------------------------------------------------ */
/*  Session / auth                                                     */
/* ------------------------------------------------------------------ */
function admin_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_set_cookie_params([
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => (($_SERVER['HTTPS'] ?? '') === 'on')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'),
    ]);
    session_start();
}

function credentials(): array
{
    if (is_file(CRED_FILE)) {
        $data = json_decode((string) file_get_contents(CRED_FILE), true);
        if (is_array($data) && !empty($data['password'])) {
            return $data;
        }
    }
    // First run: create the default account.
    $data = ['password' => password_hash(DEFAULT_PASSWORD, PASSWORD_DEFAULT), 'updated' => date('c')];
    @file_put_contents(CRED_FILE, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
    return $data;
}

function save_credentials(array $data): bool
{
    return file_put_contents(CRED_FILE, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX) !== false;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['admin_ok']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

function using_default_password(): bool
{
    return password_verify(DEFAULT_PASSWORD, credentials()['password']);
}

function attempt_login(string $password): bool
{
    $attempts = (int) ($_SESSION['fail_count'] ?? 0);
    $last = (int) ($_SESSION['fail_at'] ?? 0);
    if ($attempts >= 5 && time() - $last < 60) {
        return false;
    }
    if (password_verify($password, credentials()['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_ok'] = true;
        $_SESSION['fail_count'] = 0;
        return true;
    }
    $_SESSION['fail_count'] = $attempts + 1;
    $_SESSION['fail_at'] = time();
    return false;
}

function logout(): void
{
    $_SESSION = [];
    session_destroy();
}

/* ------------------------------------------------------------------ */
/*  CSRF / flash / helpers                                             */
/* ------------------------------------------------------------------ */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(24));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function check_csrf(): void
{
    $token = (string) ($_POST['_csrf'] ?? '');
    if (!hash_equals(csrf_token(), $token)) {
        http_response_code(419);
        exit('页面已过期，请返回重新提交。');
    }
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function flash(string $message, string $type = 'ok'): void
{
    $_SESSION['flash'][] = ['m' => $message, 't' => $type];
}

function take_flashes(): array
{
    $list = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $list;
}

function admin_url(string $file = ''): string
{
    return '/admin/' . $file;
}

/** Split a textarea into a clean list of non-empty lines. */
function text_lines(string $raw): array
{
    $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];
    $out = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line !== '') {
            $out[] = $line;
        }
    }
    return $out;
}

/** Deep write by path list. */
function deep_set(array &$node, array $path, $value): void
{
    $cursor = &$node;
    foreach ($path as $i => $key) {
        if ($i === count($path) - 1) {
            $cursor[$key] = $value;
            return;
        }
        if (!isset($cursor[$key]) || !is_array($cursor[$key])) {
            $cursor[$key] = [];
        }
        $cursor = &$cursor[$key];
    }
}

/** Deep read by dot path. */
function deep_get(array $node, string $path, $default = '')
{
    foreach (explode('.', $path) as $key) {
        if (!is_array($node) || !array_key_exists($key, $node)) {
            return $default;
        }
        $node = $node[$key];
    }
    return $node;
}

/* ------------------------------------------------------------------ */
/*  Saving                                                             */
/* ------------------------------------------------------------------ */
function backup_content(): void
{
    if (!is_file(CONTENT_FILE)) {
        return;
    }
    if (!is_dir(BACKUP_DIR)) {
        @mkdir(BACKUP_DIR, 0775, true);
    }
    @copy(CONTENT_FILE, BACKUP_DIR . '/content-' . date('Ymd-His') . '.json');

    $files = glob(BACKUP_DIR . '/content-*.json') ?: [];
    sort($files);
    while (count($files) > 40) {
        @unlink(array_shift($files));
    }
}

function save_all(array $data): bool
{
    backup_content();
    return save_content($data);
}

/* ------------------------------------------------------------------ */
/*  Images                                                             */
/* ------------------------------------------------------------------ */
/**
 * Store an uploaded image as JPEG. Returns an error message, or null on success.
 */
function store_image(array $file, string $destRel, int $maxDim = 2400): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return '没有选择文件。';
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return '上传失败（错误码 ' . (int) $file['error'] . '），可能是文件超过服务器限制。';
    }
    if ($file['size'] > 12 * 1024 * 1024) {
        return '图片超过 12MB，请压缩后再上传。';
    }
    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        return '这不是有效的图片文件。';
    }
    [$width, $height] = $info;
    $type = $info[2];
    if (!in_array($type, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
        return '只支持 JPG / PNG / WEBP 格式。';
    }

    $src = match ($type) {
        IMAGETYPE_JPEG => @imagecreatefromjpeg($file['tmp_name']),
        IMAGETYPE_PNG => @imagecreatefrompng($file['tmp_name']),
        IMAGETYPE_WEBP => @imagecreatefromwebp($file['tmp_name']),
        default => false,
    };
    if (!$src) {
        return '图片读取失败，请换一张再试。';
    }

    $scale = min(1, $maxDim / max($width, $height));
    $w = max(1, (int) round($width * $scale));
    $h = max(1, (int) round($height * $scale));

    $dst = imagecreatetruecolor($w, $h);
    // White background so PNG transparency does not turn black in JPEG.
    imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
    imagedestroy($src);

    $destAbs = ROOT . '/assets/' . ltrim($destRel, '/');
    $dir = dirname($destAbs);
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    $ok = imagejpeg($dst, $destAbs, 88);
    imagedestroy($dst);
    if (!$ok) {
        return '保存失败，请检查 assets 目录是否可写。';
    }

    // Drop alternative formats so the new picture is the one that renders.
    foreach (['png', 'webp', 'jpeg'] as $ext) {
        $other = preg_replace('/\.[a-z]+$/i', '.' . $ext, $destAbs);
        if ($other !== $destAbs && is_file($other)) {
            @unlink($other);
        }
    }
    return null;
}

function human_time(string $iso): string
{
    $ts = strtotime($iso);
    return $ts ? date('Y-m-d H:i', $ts) : $iso;
}
