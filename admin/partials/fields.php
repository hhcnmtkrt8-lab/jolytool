<?php
declare(strict_types=1);

/**
 * Renders one editable field.
 * $i      字段序号，用于 POST 时的唯一键；真正的字段名放在隐藏域里。
 * $prefix POST 数组前缀，例如 fields、fields[en]。
 */
function field_control(int $i, array $field, $value, string $prefix = 'fields'): string
{
    $key = (string) $field['key'];
    $type = (string) $field['type'];
    $name = $prefix . '[' . $i . ']';
    $uid = 'f' . preg_replace('/[^a-zA-Z0-9]/', '', $prefix) . $i;
    $help = (string) ($field['help'] ?? '');
    $out = '<div class="field">';
    $out .= '<input type="hidden" name="' . $name . '[key]" value="' . e($key) . '">';
    $out .= '<label for="' . $uid . '">' . e($field['label'] ?? $key);
    if ($help !== '') {
        $out .= '<span class="field__help">' . e($help) . '</span>';
    }
    $out .= '</label>';

    switch ($type) {
        case 'textarea':
            $out .= '<textarea id="' . $uid . '" name="' . $name . '[value]" rows="' . (int) ($field['rows'] ?? 3) . '">'
                . e(is_array($value) ? '' : (string) $value) . '</textarea>';
            break;

        case 'lines':
            $text = is_array($value) ? implode("\n", $value) : (string) $value;
            $out .= '<textarea id="' . $uid . '" name="' . $name . '[value]" rows="' . (int) ($field['rows'] ?? 3) . '">'
                . e($text) . '</textarea>';
            break;

        case 'csv':
            $text = is_array($value) ? implode(', ', $value) : (string) $value;
            $out .= '<input id="' . $uid . '" type="text" name="' . $name . '[value]" value="' . e($text) . '">';
            break;

        case 'rows':
            $cols = $field['cols'] ?? [];
            $list = is_array($value) ? array_values($value) : [];
            $rowsId = 'rows' . $uid;
            $out .= '<table class="table"><tbody id="' . $rowsId . '">';
            foreach ($list as $r => $row) {
                $out .= '<tr>';
                foreach ($cols as $col => $label) {
                    $cell = is_array($row) ? (string) ($row[$col] ?? '') : '';
                    $out .= '<td><input type="text" name="' . $name . '[rows][' . $r . '][' . e($col) . ']" value="' . e($cell) . '" placeholder="' . e($label) . '"></td>';
                }
                $out .= '<td style="width:56px"><button type="button" class="btn btn--ghost btn--sm" data-del-row>删除</button></td>';
                $out .= '</tr>';
            }
            $out .= '<template><tr>';
            foreach ($cols as $col => $label) {
                $out .= '<td><input type="text" name="' . $name . '[rows][__INDEX__][' . e($col) . ']" value="" placeholder="' . e($label) . '"></td>';
            }
            $out .= '<td style="width:56px"><button type="button" class="btn btn--ghost btn--sm" data-del-row>删除</button></td>';
            $out .= '</tr></template>';
            $out .= '</tbody></table>';
            $out .= '<p style="margin:10px 0 0"><button type="button" class="btn btn--ghost btn--sm" data-add-row="#' . $rowsId . '">＋ 添加一行</button></p>';
            break;

        case 'table':
            $out .= '<textarea id="' . $uid . '" name="' . $name . '[value]" rows="' . (int) ($field['rows'] ?? 8) . '" class="mono" style="font-family:ui-monospace,Consolas,monospace;font-size:12.5px">'
                . e(is_array($value) ? implode("\n", $value) : (string) $value) . '</textarea>';
            break;

        default:
            $out .= '<input id="' . $uid . '" type="text" name="' . $name . '[value]" value="'
                . e(is_array($value) ? '' : (string) $value) . '">';
    }

    $out .= '</div>';
    return $out;
}

/** Normalise a submitted value according to the field definition. */
function normalize_value(array $field, $value)
{
    switch ($field['type']) {
        case 'lines':
            return text_lines((string) $value);

        case 'csv':
            return array_values(array_filter(array_map('trim', explode(',', (string) $value)), static fn ($v) => $v !== ''));

        case 'rows':
            $cols = array_keys($field['cols'] ?? []);
            $rows = [];
            foreach ((array) $value as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $clean = [];
                $hasValue = false;
                foreach ($cols as $col) {
                    $cell = trim((string) ($row[$col] ?? ''));
                    if ($cell !== '') {
                        $hasValue = true;
                    }
                    $clean[$col] = $cell;
                }
                if ($hasValue) {
                    $rows[] = $clean;
                }
            }
            return $rows;

        case 'table':
            $parsed = parse_spec_table((string) $value);
            return $parsed;

        default:
            return trim((string) $value);
    }
}
