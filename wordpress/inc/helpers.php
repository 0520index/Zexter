<?php
/**
 * コンテンツ取得ヘルパー
 */
if (!defined('ABSPATH')) {
  exit;
}

function zexter_content(): array
{
  static $cache = null;
  if ($cache !== null) {
    return $cache;
  }
  $saved = get_option('zexter_content', []);
  if (!is_array($saved)) {
    $saved = [];
  }
  $defaults = zexter_default_content();
  $merged = array_merge($defaults, $saved);
  if (!empty($saved['services']) && is_array($saved['services'])) {
    $merged['services'] = $saved['services'];
  }
  $cache = $merged;
  return $cache;
}

function zexter_get(string $key, $default = ''): string
{
  $all = zexter_content();
  if (!array_key_exists($key, $all) || is_array($all[$key])) {
    if ($default !== '') {
      return (string) $default;
    }
    $defaults = zexter_default_content();
    return isset($defaults[$key]) && is_string($defaults[$key]) ? $defaults[$key] : '';
  }
  return (string) $all[$key];
}

/**
 * 改行を <br> にして安全に出力
 */
function zexter_e(string $key, bool $nl2br = true): void
{
  $text = zexter_get($key);
  if ($nl2br) {
    echo nl2br(esc_html($text), false);
  } else {
    echo esc_html($text);
  }
}

/**
 * 空行で段落分けして出力
 */
function zexter_e_paras(string $key): void
{
  echo wpautop(esc_html(zexter_get($key)));
}

/**
 * 2行目を金色にする見出し
 */
function zexter_e_gold_heading(string $key): void
{
  $lines = preg_split('/\r\n|\r|\n/', zexter_get($key)) ?: [];
  $lines = array_values(array_filter(array_map('trim', $lines), static fn($l) => $l !== ''));
  if (!$lines) {
    return;
  }
  echo esc_html($lines[0]);
  if (isset($lines[1])) {
    echo '<br /><span class="heading--gold">' . esc_html($lines[1]) . '</span>';
  }
  for ($i = 2, $n = count($lines); $i < $n; $i++) {
    echo '<br />' . esc_html($lines[$i]);
  }
}

function zexter_services(): array
{
  $all = zexter_content();
  $list = $all['services'] ?? [];
  if (!is_array($list) || !$list) {
    return zexter_default_content()['services'];
  }
  $out = [];
  foreach ($list as $i => $row) {
    if (!is_array($row)) {
      continue;
    }
    $title = trim((string) ($row['title'] ?? ''));
    if ($title === '') {
      continue;
    }
    $id = trim((string) ($row['id'] ?? ''));
    if ($id === '') {
      $id = 'service-' . ($i + 1);
    }
    $out[] = [
      'id' => sanitize_title($id),
      'label' => (string) ($row['label'] ?? $title),
      'title' => $title,
      'short' => (string) ($row['short'] ?? ''),
      'body' => (string) ($row['body'] ?? ''),
      'tags' => (string) ($row['tags'] ?? ''),
    ];
  }
  return $out ?: zexter_default_content()['services'];
}

function zexter_orbit_angles(int $count): array
{
  if ($count <= 0) {
    return [];
  }
  if ($count === 5) {
    return [-90, -18, 54, 126, 198];
  }
  $angles = [];
  for ($i = 0; $i < $count; $i++) {
    $angles[] = (int) round(-90 + (360 / $count) * $i);
  }
  return $angles;
}
