<?php
/**
 * ナビゲーション（管理画面のメニュー連携）
 */
if (!defined('ABSPATH')) {
  exit;
}

/**
 * フラットな <a> 出力用ウォーカー（既存デザインに合わせる）
 */
class Zexter_Nav_Walker extends Walker_Nav_Menu
{
  public function start_lvl(&$output, $depth = 0, $args = null)
  {
  }

  public function end_lvl(&$output, $depth = 0, $args = null)
  {
  }

  public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
  {
    $item = $data_object;
    $classes = empty($item->classes) ? [] : (array) $item->classes;
    $active = '';
    foreach (['current-menu-item', 'current_page_item', 'current-menu-ancestor', 'current-menu-parent', 'current_page_ancestor'] as $c) {
      if (in_array($c, $classes, true)) {
        $active = 'is-active';
        break;
      }
    }
    $atts = [
      'href' => !empty($item->url) ? $item->url : '',
    ];
    if ($active !== '') {
      $atts['class'] = $active;
    }
    if (!empty($item->attr_title)) {
      $atts['title'] = $item->attr_title;
    }
    if (!empty($item->target)) {
      $atts['target'] = $item->target;
    }
    if (!empty($item->xfn)) {
      $atts['rel'] = $item->xfn;
    }

    $attributes = '';
    foreach ($atts as $attr => $value) {
      if ($value === '') {
        continue;
      }
      $value = 'href' === $attr ? esc_url($value) : esc_attr($value);
      $attributes .= ' ' . $attr . '="' . $value . '"';
    }

    $title = apply_filters('the_title', $item->title, $item->ID);
    $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

    $output .= '<a' . $attributes . '>' . esc_html($title) . '</a>';
  }

  public function end_el(&$output, $data_object, $depth = 0, $args = null)
  {
  }
}

/**
 * メニュー未設定時のフォールバック
 */
function zexter_nav_fallback(): void
{
  $items = [
    ['home', home_url('/'), 'ホーム'],
    ['about', zexter_page_url('about'), '会社情報'],
    ['services', zexter_page_url('services'), '事業内容'],
    ['contact', zexter_page_url('contact'), 'お問い合わせ'],
  ];
  foreach ($items as [$slug, $url, $label]) {
    $class = zexter_nav_class($slug);
    printf(
      '<a class="%1$s" href="%2$s">%3$s</a>',
      esc_attr($class),
      esc_url($url),
      esc_html($label)
    );
  }
}

/**
 * メニュー描画
 */
function zexter_nav(string $location = 'primary'): void
{
  if (has_nav_menu($location)) {
    wp_nav_menu([
      'theme_location' => $location,
      'container' => false,
      'items_wrap' => '%3$s',
      'depth' => 1,
      'walker' => new Zexter_Nav_Walker(),
      'fallback_cb' => 'zexter_nav_fallback',
    ]);
    return;
  }
  zexter_nav_fallback();
}

add_action('after_setup_theme', function () {
  register_nav_menus([
    'primary' => 'ヘッダー・フッター共通メニュー',
  ]);
  add_post_type_support('page', 'excerpt');
});

/**
 * テーマ有効化時：メニューが空なら初期4ページを登録
 */
add_action('after_switch_theme', function () {
  if (has_nav_menu('primary')) {
    return;
  }

  $menu_name = 'Zexterメイン';
  $menu_id = wp_create_nav_menu($menu_name);
  if (is_wp_error($menu_id)) {
    return;
  }

  $home_id = (int) get_option('page_on_front');
  if ($home_id) {
    wp_update_nav_menu_item($menu_id, 0, [
      'menu-item-title' => 'ホーム',
      'menu-item-object' => 'page',
      'menu-item-object-id' => $home_id,
      'menu-item-type' => 'post_type',
      'menu-item-status' => 'publish',
    ]);
  } else {
    wp_update_nav_menu_item($menu_id, 0, [
      'menu-item-title' => 'ホーム',
      'menu-item-url' => home_url('/'),
      'menu-item-type' => 'custom',
      'menu-item-status' => 'publish',
    ]);
  }

  foreach (['about' => '会社情報', 'services' => '事業内容', 'contact' => 'お問い合わせ'] as $slug => $label) {
    $page = get_page_by_path($slug);
    if (!$page) {
      continue;
    }
    wp_update_nav_menu_item($menu_id, 0, [
      'menu-item-title' => $label,
      'menu-item-object' => 'page',
      'menu-item-object-id' => $page->ID,
      'menu-item-type' => 'post_type',
      'menu-item-status' => 'publish',
    ]);
  }

  $locations = get_theme_mod('nav_menu_locations', []);
  if (!is_array($locations)) {
    $locations = [];
  }
  $locations['primary'] = (int) $menu_id;
  set_theme_mod('nav_menu_locations', $locations);
});
