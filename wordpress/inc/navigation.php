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
    foreach (['current-menu-item', 'current_page_item', 'current-menu-ancestor', 'current-menu-parent', 'current_page_ancestor', 'current-menu-item'] as $c) {
      if (in_array($c, $classes, true)) {
        $active = 'is-active';
        break;
      }
    }
    // お知らせアーカイブへのカスタムリンク
    if ($active === '' && (is_post_type_archive('zexter_news') || is_singular('zexter_news'))) {
      $news_url = untrailingslashit(zexter_news_url());
      $item_url = untrailingslashit((string) $item->url);
      if ($news_url !== '' && $item_url === $news_url) {
        $active = 'is-active';
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
    ['news', zexter_news_url(), 'お知らせ'],
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
