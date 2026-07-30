<?php
/**
 * お知らせカスタム投稿タイプ
 */
if (!defined('ABSPATH')) {
  exit;
}

function zexter_register_news(): void
{
  register_taxonomy('zexter_news_cat', 'zexter_news', [
    'labels' => [
      'name' => 'お知らせカテゴリ',
      'singular_name' => 'カテゴリ',
      'search_items' => 'カテゴリを検索',
      'all_items' => 'すべてのカテゴリ',
      'edit_item' => 'カテゴリを編集',
      'update_item' => 'カテゴリを更新',
      'add_new_item' => '新規カテゴリを追加',
      'new_item_name' => '新しいカテゴリ名',
      'menu_name' => 'カテゴリ',
    ],
    'public' => true,
    'hierarchical' => true,
    'show_in_rest' => true,
    'rewrite' => ['slug' => 'news-category'],
  ]);

  register_post_type('zexter_news', [
    'labels' => [
      'name' => 'お知らせ',
      'singular_name' => 'お知らせ',
      'add_new' => '新規追加',
      'add_new_item' => 'お知らせを追加',
      'edit_item' => 'お知らせを編集',
      'new_item' => '新しいお知らせ',
      'view_item' => 'お知らせを表示',
      'search_items' => 'お知らせを検索',
      'not_found' => 'お知らせが見つかりません',
      'not_found_in_trash' => 'ゴミ箱にお知らせはありません',
      'all_items' => 'お知らせ一覧',
      'menu_name' => 'お知らせ',
    ],
    'public' => true,
    'has_archive' => true,
    'rewrite' => ['slug' => 'news'],
    'menu_icon' => 'dashicons-megaphone',
    'menu_position' => 5,
    'show_in_rest' => true,
    'supports' => ['title', 'editor', 'excerpt', 'revisions'],
    'taxonomies' => ['zexter_news_cat'],
  ]);
}

add_action('init', 'zexter_register_news');
