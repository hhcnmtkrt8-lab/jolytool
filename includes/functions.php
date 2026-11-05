<?php
declare(strict_types=1);

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Text with line breaks preserved, escaped. */
function e_nl($value): string
{
    return nl2br(e($value), false);
}

function post(string $key, string $default = ''): string
{
    $v = $_POST[$key] ?? $default;
    if (is_array($v)) {
        return $default;
    }
    return trim((string) $v);
}

/** Split a textarea into non-empty trimmed lines. */
function lines(string $value): array
{
    $out = [];
    foreach (preg_split('/\r\n|\r|\n/', $value) as $line) {
        $line = trim($line);
        if ($line !== '') {
            $out[] = $line;
        }
    }
    return $out;
}

/**
 * Parse a spec table typed as "cell | cell | cell".
 * Returns ['cols' => [...], 'rows' => [[...], ...]].
 */
function parse_spec_table(string $value): array
{
    $rows = [];
    foreach (lines($value) as $line) {
        $cells = array_map('trim', explode('|', $line));
        $rows[] = $cells;
    }
    $cols = $rows ? $rows[0] : [];
    return ['cols' => $cols, 'rows' => array_slice($rows, 1)];
}

/** Render a spec table back into the "cell | cell" text form for the editor. */
function spec_table_text(array $spec): string
{
    $out = [];
    if (!empty($spec['cols'])) {
        $out[] = implode(' | ', $spec['cols']);
    }
    foreach ($spec['rows'] ?? [] as $row) {
        $out[] = implode(' | ', $row);
    }
    return implode("\n", $out);
}
