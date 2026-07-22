<?php
/**
 * Meta box: ページ上部の英字ラベル（任意）
 */
if (!defined('ABSPATH')) {
  exit;
}

add_action('add_meta_boxes', function () {
  add_meta_box(
    'zexter_page_meta',
    'Zexter ページ設定',
    'zexter_render_page_meta_box',
    'page',
    'side',
    'default'
  );
});

function zexter_render_page_meta_box(WP_Post $post): void
{
  wp_nonce_field('zexter_page_meta', 'zexter_page_meta_nonce');
  $eyebrow = get_post_meta($post->ID, 'zexter_eyebrow', true);
  if (!is_string($eyebrow)) {
    $eyebrow = '';
  }
  echo '<p><label for="zexter_eyebrow"><strong>英字ラベル</strong>（ページ上部）</label></p>';
  printf(
    '<input type="text" id="zexter_eyebrow" name="zexter_eyebrow" value="%s" class="widefat" placeholder="例: RECRUIT" />',
    esc_attr($eyebrow)
  );
  echo '<p class="description">空欄なら「PAGE」と表示されます。会社情報などの専用テンプレートでは使いません。</p>';
}

add_action('save_post_page', function ($post_id) {
  if (!isset($_POST['zexter_page_meta_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['zexter_page_meta_nonce'])), 'zexter_page_meta')) {
    return;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  if (!current_user_can('edit_page', $post_id)) {
    return;
  }
  if (!isset($_POST['zexter_eyebrow'])) {
    return;
  }
  $val = sanitize_text_field(wp_unslash($_POST['zexter_eyebrow']));
  if ($val === '') {
    delete_post_meta($post_id, 'zexter_eyebrow');
  } else {
    update_post_meta($post_id, 'zexter_eyebrow', $val);
  }
});
