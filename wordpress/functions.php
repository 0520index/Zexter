<?php
/**
 * Zexter theme functions
 */

if (!defined('ABSPATH')) {
  exit;
}

define('ZEXTER_VERSION', '2.0.0');

require_once get_template_directory() . '/inc/defaults.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/news.php';
require_once get_template_directory() . '/inc/admin-settings.php';
require_once get_template_directory() . '/inc/navigation.php';
require_once get_template_directory() . '/inc/block-patterns.php';
require_once get_template_directory() . '/inc/page-meta.php';
require_once get_template_directory() . '/inc/setup.php';

/**
 * 固定ページのURLをスラッグから取得
 */
function zexter_page_url(string $slug): string
{
  $page = get_page_by_path($slug);
  if ($page) {
    return get_permalink($page);
  }
  return home_url('/' . $slug . '/');
}

/**
 * ナビの現在ページ判定用クラス
 */
function zexter_nav_class(string $slug): string
{
  if ($slug === 'home') {
    return is_front_page() ? 'is-active' : '';
  }
  if ($slug === 'news') {
    return (is_post_type_archive('zexter_news') || is_singular('zexter_news')) ? 'is-active' : '';
  }
  return is_page($slug) ? 'is-active' : '';
}

add_action('after_setup_theme', function () {
  add_theme_support('title-tag');
  add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
  add_theme_support('responsive-embeds');
  add_theme_support('wp-block-styles');
  add_theme_support('post-thumbnails');
});

add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'zexter-fonts',
    'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Syne:wght@700;800&family=Zen+Kaku+Gothic+New:wght@400;500;700&display=swap',
    [],
    null
  );

  wp_enqueue_style(
    'zexter-style',
    get_template_directory_uri() . '/css/style.css',
    ['zexter-fonts'],
    ZEXTER_VERSION
  );

  wp_enqueue_script(
    'zexter-main',
    get_template_directory_uri() . '/js/main.js',
    [],
    ZEXTER_VERSION,
    true
  );

  if (is_front_page()) {
    wp_enqueue_script(
      'zexter-home',
      get_template_directory_uri() . '/js/home.js',
      ['zexter-main'],
      ZEXTER_VERSION,
      true
    );
  }

  if (is_page('services') || is_page_template('page-services.php')) {
    wp_add_inline_script(
      'zexter-main',
      "(function(){var id=location.hash.replace('#','');if(!id)return;var target=document.getElementById(id);if(!target)return;setTimeout(function(){target.scrollIntoView({behavior:'smooth',block:'center'});},200);})();"
    );
  }
});

add_filter('document_title_parts', function ($parts) {
  if (is_front_page()) {
    $parts['title'] = zexter_get('home_document_title');
    unset($parts['tagline'], $parts['site']);
  } elseif (is_post_type_archive('zexter_news')) {
    $parts['title'] = 'お知らせ';
  }
  return $parts;
});

add_action('wp_head', function () {
  $icon = get_template_directory_uri() . '/images/favicon.ico';
  $apple = get_template_directory_uri() . '/images/apple-touch-icon.png';
  $android = get_template_directory_uri() . '/images/android-chrome-256×256.png';
  echo '<link rel="shortcut icon" href="' . esc_url($icon) . '" type="image/x-icon">' . "\n";
  echo '<link rel="apple-touch-icon" href="' . esc_url($apple) . '">' . "\n";
  echo '<link rel="icon" type="image/png" href="' . esc_url($android) . '" sizes="256x256">' . "\n";
}, 1);

add_action('admin_notices', function () {
  if (!current_user_can('edit_theme_options')) {
    return;
  }
  $screen = function_exists('get_current_screen') ? get_current_screen() : null;
  if (!$screen || !in_array($screen->id, ['themes', 'dashboard'], true)) {
    return;
  }
  $settings = admin_url('themes.php?page=zexter-settings');
  $menus = admin_url('nav-menus.php');
  $news = admin_url('edit.php?post_type=zexter_news');
  $pages = admin_url('edit.php?post_type=page');
  echo '<div class="notice notice-info"><p><strong>Zexter:</strong> 文言・リストは <a href="' . esc_url($settings) . '">外観 → Zexter設定</a>、お知らせは <a href="' . esc_url($news) . '">お知らせ</a>、ページ追加は <a href="' . esc_url($pages) . '">固定ページ</a>、ナビは <a href="' . esc_url($menus) . '">外観 → メニュー</a> から操作できます。</p></div>';
});
