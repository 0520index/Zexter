<?php
/**
 * Zexter theme functions
 */

if (!defined('ABSPATH')) {
  exit;
}

define('ZEXTER_VERSION', '1.2.0');

require_once get_template_directory() . '/inc/defaults.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/admin-settings.php';
require_once get_template_directory() . '/inc/navigation.php';
require_once get_template_directory() . '/inc/block-patterns.php';
require_once get_template_directory() . '/inc/page-meta.php';

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
  return is_page($slug) ? 'is-active' : '';
}

add_action('after_setup_theme', function () {
  add_theme_support('title-tag');
  add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
  add_theme_support('responsive-embeds');
  add_theme_support('wp-block-styles');
});

add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'zexter-fonts',
    'https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Zen+Kaku+Gothic+New:wght@400;500;700&display=swap',
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
      "(function(){var id=location.hash.replace('#','');if(!id)return;var panel=document.getElementById(id);if(!panel)return;panel.classList.add('is-open');var t=panel.querySelector('.service-panel__trigger');if(t)t.setAttribute('aria-expanded','true');setTimeout(function(){panel.scrollIntoView({behavior:'smooth',block:'center'});},200);})();"
    );
  }
});

add_filter('document_title_parts', function ($parts) {
  if (is_front_page()) {
    $parts['title'] = zexter_get('home_document_title');
    unset($parts['tagline'], $parts['site']);
  }
  return $parts;
});

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
  echo '<div class="notice notice-info"><p><strong>Zexter:</strong> 文言は <a href="' . esc_url($settings) . '">外観 → Zexter設定</a>、ページの追加・ナビは <a href="' . esc_url($menus) . '">外観 → メニュー</a> と固定ページから操作できます。</p></div>';
});
