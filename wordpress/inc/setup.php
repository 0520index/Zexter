<?php
/**
 * テーマ有効化時の初期セットアップ（ページ・お知らせ・メニュー）
 */
if (!defined('ABSPATH')) {
  exit;
}

/**
 * 固定ページをスラッグで確保
 */
function zexter_ensure_page(string $slug, string $title, string $template = '', string $excerpt = ''): int
{
  $existing = get_page_by_path($slug);
  if ($existing) {
    $id = (int) $existing->ID;
    if ($template !== '') {
      update_post_meta($id, '_wp_page_template', $template);
    }
    return $id;
  }

  $id = wp_insert_post([
    'post_title' => $title,
    'post_name' => $slug,
    'post_status' => 'publish',
    'post_type' => 'page',
    'post_content' => '',
    'post_excerpt' => $excerpt,
  ], true);

  if (is_wp_error($id) || !$id) {
    return 0;
  }

  if ($template !== '') {
    update_post_meta((int) $id, '_wp_page_template', $template);
  }

  return (int) $id;
}

/**
 * お知らせカテゴリを確保
 */
function zexter_ensure_news_term(string $name, string $slug): int
{
  $term = term_exists($slug, 'zexter_news_cat');
  if ($term) {
    return (int) (is_array($term) ? $term['term_id'] : $term);
  }
  $created = wp_insert_term($name, 'zexter_news_cat', ['slug' => $slug]);
  if (is_wp_error($created)) {
    return 0;
  }
  return (int) $created['term_id'];
}

/**
 * 初期お知らせを投入
 */
function zexter_seed_news(): void
{
  $existing = get_posts([
    'post_type' => 'zexter_news',
    'posts_per_page' => 1,
    'post_status' => 'any',
    'fields' => 'ids',
  ]);
  if ($existing) {
    return;
  }

  $cat_notice = zexter_ensure_news_term('お知らせ', 'oshirase');
  $cat_service = zexter_ensure_news_term('サービス', 'service');

  $items = [
    [
      'title' => '公式ホームページを公開しました',
      'date' => '2026-07-23 10:00:00',
      'cat' => $cat_notice,
      'content' => '株式会社ZEXTERの公式サイトを公開いたしました。会社情報・事業内容・お問い合わせは本サイトよりご確認いただけます。',
    ],
    [
      'title' => '株式会社ZEXTERとして事業を開始しました',
      'date' => '2026-07-07 10:00:00',
      'cat' => $cat_notice,
      'content' => '法人として新たな体制のもと、害鳥・害虫・害獣駆除、電気工事、清掃、買い取り販売、広告など各サービスの提供を開始いたしました。',
    ],
    [
      'title' => '各事業のご相談受付を開始しています',
      'date' => '2026-06-15 10:00:00',
      'cat' => $cat_service,
      'content' => '駆除・電気工事・清掃・買い取り・広告について、お問い合わせフォームよりご相談を受け付けております。分野が分からない場合もお気軽にご連絡ください。',
    ],
  ];

  foreach ($items as $item) {
    $id = wp_insert_post([
      'post_title' => $item['title'],
      'post_content' => $item['content'],
      'post_status' => 'publish',
      'post_type' => 'zexter_news',
      'post_date' => $item['date'],
      'post_date_gmt' => get_gmt_from_date($item['date']),
    ], true);
    if (!is_wp_error($id) && $id && $item['cat']) {
      wp_set_object_terms((int) $id, [(int) $item['cat']], 'zexter_news_cat');
    }
  }
}

add_action('after_switch_theme', function () {
  // 固定ページ
  $home_id = zexter_ensure_page('home', 'ホーム');
  $about_id = zexter_ensure_page('about', '会社情報', 'page-about.php', zexter_get('about_lead'));
  $services_id = zexter_ensure_page('services', '事業内容', 'page-services.php', zexter_get('services_lead'));
  $contact_id = zexter_ensure_page('contact', 'お問い合わせ', 'page-contact.php', zexter_get('contact_lead'));

  if ($home_id) {
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_id);
  }

  // お知らせ（テーマ切替時は init 前のため明示登録）
  zexter_register_news();
  zexter_seed_news();
  flush_rewrite_rules();

  // メニュー
  if (has_nav_menu('primary')) {
    return;
  }

  $menu_name = 'Zexterメイン';
  $menu_id = wp_create_nav_menu($menu_name);
  if (is_wp_error($menu_id)) {
    return;
  }

  if ($home_id) {
    wp_update_nav_menu_item($menu_id, 0, [
      'menu-item-title' => 'ホーム',
      'menu-item-object' => 'page',
      'menu-item-object-id' => $home_id,
      'menu-item-type' => 'post_type',
      'menu-item-status' => 'publish',
    ]);
  }

  foreach (
    [
      [$about_id, '会社情報'],
      [$services_id, '事業内容'],
    ] as [$pid, $label]
  ) {
    if (!$pid) {
      continue;
    }
    wp_update_nav_menu_item($menu_id, 0, [
      'menu-item-title' => $label,
      'menu-item-object' => 'page',
      'menu-item-object-id' => $pid,
      'menu-item-type' => 'post_type',
      'menu-item-status' => 'publish',
    ]);
  }

  wp_update_nav_menu_item($menu_id, 0, [
    'menu-item-title' => 'お知らせ',
    'menu-item-url' => zexter_news_url(),
    'menu-item-type' => 'custom',
    'menu-item-status' => 'publish',
  ]);

  if ($contact_id) {
    wp_update_nav_menu_item($menu_id, 0, [
      'menu-item-title' => 'お問い合わせ',
      'menu-item-object' => 'page',
      'menu-item-object-id' => $contact_id,
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
