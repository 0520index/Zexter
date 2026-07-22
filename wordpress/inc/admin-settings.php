<?php
/**
 * 管理画面: 外観 → Zexter設定
 */
if (!defined('ABSPATH')) {
  exit;
}

add_action('admin_menu', function () {
  add_theme_page(
    'Zexter設定',
    'Zexter設定',
    'edit_theme_options',
    'zexter-settings',
    'zexter_render_settings_page'
  );
});

add_action('admin_enqueue_scripts', function ($hook) {
  if ($hook !== 'appearance_page_zexter-settings') {
    return;
  }
  wp_enqueue_style(
    'zexter-admin',
    get_template_directory_uri() . '/css/admin.css',
    [],
    ZEXTER_VERSION
  );
});

function zexter_sanitize_content(array $input): array
{
  $defaults = zexter_default_content();
  $out = [];

  $text_keys = array_keys($defaults);
  foreach ($text_keys as $key) {
    if ($key === 'services') {
      continue;
    }
    if (!isset($input[$key])) {
      continue;
    }
    $out[$key] = sanitize_textarea_field(wp_unslash($input[$key]));
  }

  $services = [];
  if (!empty($input['services']) && is_array($input['services'])) {
    foreach ($input['services'] as $row) {
      if (!is_array($row)) {
        continue;
      }
      $title = sanitize_text_field(wp_unslash($row['title'] ?? ''));
      if ($title === '') {
        continue;
      }
      $id_raw = sanitize_text_field(wp_unslash($row['id'] ?? ''));
      $services[] = [
        'id' => $id_raw !== '' ? sanitize_title($id_raw) : sanitize_title($title),
        'label' => sanitize_text_field(wp_unslash($row['label'] ?? '')),
        'title' => $title,
        'short' => sanitize_textarea_field(wp_unslash($row['short'] ?? '')),
        'body' => sanitize_textarea_field(wp_unslash($row['body'] ?? '')),
        'tags' => sanitize_textarea_field(wp_unslash($row['tags'] ?? '')),
      ];
    }
  }
  $out['services'] = $services;

  return $out;
}

add_action('admin_init', function () {
  if (!isset($_POST['zexter_settings_nonce'])) {
    return;
  }
  if (!current_user_can('edit_theme_options')) {
    return;
  }
  if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['zexter_settings_nonce'])), 'zexter_save_settings')) {
    return;
  }

  if (!empty($_POST['zexter_reset_defaults'])) {
    delete_option('zexter_content');
  } else {
    $raw = isset($_POST['zexter']) && is_array($_POST['zexter']) ? $_POST['zexter'] : [];
    $clean = zexter_sanitize_content($raw);
    update_option('zexter_content', $clean, false);
  }

  wp_safe_redirect(add_query_arg([
    'page' => 'zexter-settings',
    'updated' => '1',
  ], admin_url('themes.php')));
  exit;
});

function zexter_field(string $key, string $label, string $type = 'text', string $hint = ''): void
{
  $value = zexter_get($key);
  $name = 'zexter[' . esc_attr($key) . ']';
  echo '<div class="zexter-field">';
  echo '<label for="' . esc_attr($key) . '">' . esc_html($label) . '</label>';
  if ($type === 'textarea') {
    printf(
      '<textarea id="%1$s" name="%2$s" rows="4">%3$s</textarea>',
      esc_attr($key),
      $name,
      esc_textarea($value)
    );
  } else {
    printf(
      '<input type="text" id="%1$s" name="%2$s" value="%3$s" class="regular-text" />',
      esc_attr($key),
      $name,
      esc_attr($value)
    );
  }
  if ($hint !== '') {
    echo '<p class="description">' . esc_html($hint) . '</p>';
  }
  echo '</div>';
}

function zexter_render_settings_page(): void
{
  if (!current_user_can('edit_theme_options')) {
    return;
  }
  $services = zexter_services();
  while (count($services) < 8) {
    $services[] = [
      'id' => '',
      'label' => '',
      'title' => '',
      'short' => '',
      'body' => '',
      'tags' => '',
    ];
  }
  $tab = isset($_GET['tab']) ? sanitize_key(wp_unslash($_GET['tab'])) : 'company';
  $tabs = [
    'company' => '会社・連絡先',
    'home' => 'トップページ',
    'about' => '会社情報',
    'services' => '事業内容',
    'contact' => 'お問い合わせ',
  ];
  if (!isset($tabs[$tab])) {
    $tab = 'company';
  }
  ?>
  <div class="wrap zexter-admin">
    <h1>Zexter設定</h1>
    <p class="zexter-admin__lead">サイトに表示する文言は、すべてここから変更できます。変更後は「変更を保存」を押してください。</p>

    <?php if (!empty($_GET['updated'])) : ?>
      <div class="notice notice-success is-dismissible"><p>設定を保存しました。</p></div>
    <?php endif; ?>

    <nav class="nav-tab-wrapper zexter-tabs">
      <?php foreach ($tabs as $id => $label) : ?>
        <a class="nav-tab <?php echo $tab === $id ? 'nav-tab-active' : ''; ?>"
          href="<?php echo esc_url(admin_url('themes.php?page=zexter-settings&tab=' . $id)); ?>">
          <?php echo esc_html($label); ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <form method="post" action="">
      <?php wp_nonce_field('zexter_save_settings', 'zexter_settings_nonce'); ?>

      <div class="zexter-panel" data-tab="company" <?php echo $tab === 'company' ? '' : 'hidden'; ?>>
        <h2>会社概要・連絡先</h2>
        <p class="description">会社情報ページの表と、お問い合わせ・フッターに使われます。</p>
        <?php
        zexter_field('company_name', '会社名');
        zexter_field('company_founded', '設立');
        zexter_field('company_business', '事業内容（概要行）', 'textarea');
        zexter_field('company_color', 'カラー');
        zexter_field('company_address', '所在地', 'textarea');
        zexter_field('contact_email', 'メールアドレス');
        zexter_field('contact_phone', '電話番号');
        zexter_field('contact_hours', '営業時間');
        zexter_field('footer_blurb', 'フッター説明文', 'textarea');
        zexter_field('home_document_title', 'トップのブラウザタイトル');
        zexter_field('home_meta_description', 'トップの meta description', 'textarea');
        ?>
      </div>

      <div class="zexter-panel" data-tab="home" <?php echo $tab === 'home' ? '' : 'hidden'; ?>>
        <h2>トップページ</h2>
        <?php
        zexter_field('home_hero_meta', 'ヒーロー上の英字ラベル');
        zexter_field('home_hero_headline', 'ヒーロー見出し', 'textarea', '改行すると表示でも改行されます');
        zexter_field('home_hero_copy', 'ヒーロー本文', 'textarea');
        zexter_field('home_orbit_heading', '事業軌道の見出し', 'textarea');
        zexter_field('home_orbit_lead', '事業軌道の説明', 'textarea');
        zexter_field('home_philosophy_heading', 'フィロソフィー見出し', 'textarea');
        zexter_field('home_philosophy_lead', 'フィロソフィー本文', 'textarea');
        zexter_field('home_phil_1_title', '特徴1 タイトル');
        zexter_field('home_phil_1_text', '特徴1 本文', 'textarea');
        zexter_field('home_phil_2_title', '特徴2 タイトル');
        zexter_field('home_phil_2_text', '特徴2 本文', 'textarea');
        zexter_field('home_phil_3_title', '特徴3 タイトル');
        zexter_field('home_phil_3_text', '特徴3 本文', 'textarea');
        zexter_field('home_glance_heading', 'サービス一覧見出し', 'textarea');
        zexter_field('home_coming_title', 'Coming Soon タイトル');
        zexter_field('home_coming_text', 'Coming Soon 本文', 'textarea');
        zexter_field('home_guide_title', '3ステップ見出し');
        zexter_field('home_guide_1_title', 'ステップ1 タイトル');
        zexter_field('home_guide_1_text', 'ステップ1 本文', 'textarea');
        zexter_field('home_guide_2_title', 'ステップ2 タイトル');
        zexter_field('home_guide_2_text', 'ステップ2 本文', 'textarea');
        zexter_field('home_guide_3_title', 'ステップ3 タイトル');
        zexter_field('home_guide_3_text', 'ステップ3 本文', 'textarea');
        ?>
      </div>

      <div class="zexter-panel" data-tab="about" <?php echo $tab === 'about' ? '' : 'hidden'; ?>>
        <h2>会社情報ページ</h2>
        <?php
        zexter_field('about_lead', 'ページリード文', 'textarea');
        zexter_field('about_who_heading', '紹介見出し', 'textarea');
        zexter_field('about_who_body', '紹介本文', 'textarea', '空行で段落分けできます');
        zexter_field('about_timeline_lead', '沿革の説明', 'textarea');
        zexter_field('about_tl_1_title', '沿革1 タイトル');
        zexter_field('about_tl_1_text', '沿革1 本文', 'textarea');
        zexter_field('about_tl_2_title', '沿革2 タイトル');
        zexter_field('about_tl_2_text', '沿革2 本文', 'textarea');
        zexter_field('about_tl_3_title', '沿革3 タイトル');
        zexter_field('about_tl_3_text', '沿革3 本文', 'textarea');
        zexter_field('about_meta_description', 'meta description', 'textarea');
        ?>
      </div>

      <div class="zexter-panel" data-tab="services" <?php echo $tab === 'services' ? '' : 'hidden'; ?>>
        <h2>事業内容（共通）</h2>
        <p class="description">トップの軌道・一覧、事業ページ、お問い合わせの分野選択で共通利用します。タイトルを空にするとその枠は非表示になります（最大8件）。</p>
        <?php zexter_field('services_lead', '事業ページのリード文', 'textarea'); ?>
        <?php zexter_field('services_meta_description', '事業ページ meta description', 'textarea'); ?>

        <?php foreach ($services as $i => $svc) : ?>
          <fieldset class="zexter-service">
            <legend>事業 <?php echo (int) ($i + 1); ?></legend>
            <div class="zexter-field">
              <label>ID（英数字・URL用）</label>
              <input type="text" name="zexter[services][<?php echo (int) $i; ?>][id]" value="<?php echo esc_attr($svc['id']); ?>" placeholder="例: pest" />
            </div>
            <div class="zexter-field">
              <label>短いラベル（軌道用）</label>
              <input type="text" name="zexter[services][<?php echo (int) $i; ?>][label]" value="<?php echo esc_attr($svc['label']); ?>" />
            </div>
            <div class="zexter-field">
              <label>正式タイトル</label>
              <input type="text" name="zexter[services][<?php echo (int) $i; ?>][title]" value="<?php echo esc_attr($svc['title']); ?>" />
            </div>
            <div class="zexter-field">
              <label>短い説明（トップ一覧用）</label>
              <textarea name="zexter[services][<?php echo (int) $i; ?>][short]" rows="2"><?php echo esc_textarea($svc['short']); ?></textarea>
            </div>
            <div class="zexter-field">
              <label>詳細説明（事業ページ）</label>
              <textarea name="zexter[services][<?php echo (int) $i; ?>][body]" rows="3"><?php echo esc_textarea($svc['body']); ?></textarea>
            </div>
            <div class="zexter-field">
              <label>タグ（1行に1つ）</label>
              <textarea name="zexter[services][<?php echo (int) $i; ?>][tags]" rows="3"><?php echo esc_textarea($svc['tags']); ?></textarea>
            </div>
          </fieldset>
        <?php endforeach; ?>
      </div>

      <div class="zexter-panel" data-tab="contact" <?php echo $tab === 'contact' ? '' : 'hidden'; ?>>
        <h2>お問い合わせページ</h2>
        <?php
        zexter_field('contact_lead', 'ページリード文', 'textarea');
        zexter_field('contact_guide_heading', '左カラム見出し', 'textarea');
        zexter_field('contact_guide_note', '記入のヒント', 'textarea');
        zexter_field('contact_email_note', 'メール下の注記', 'textarea');
        zexter_field('contact_phone_note', '電話下の注記', 'textarea');
        zexter_field('contact_hours_note', '営業時間下の注記', 'textarea');
        zexter_field('contact_form_note', 'フォーム下の注記', 'textarea');
        zexter_field('contact_cf7', 'Contact Form 7 ショートコード', 'text', '例: [contact-form-7 id="123" title="お問い合わせ"] 空ならデモフォームを表示');
        zexter_field('contact_meta_description', 'meta description', 'textarea');
        ?>
      </div>

      <?php
      // 他タブの値もPOSTに含めるため、非表示タブのフィールドは hidden ではなく
      // 「保存時は表示タブのみ」だと他が消える。→ 全パネルをDOMに残し hidden 属性のみ。
      // ただし hidden パネル内の input は submit される。OK。
      ?>

      <p class="submit">
        <button type="submit" class="button button-primary">変更を保存</button>
      </p>
    </form>

    <hr />
    <form method="post" action="" onsubmit="return confirm('すべての文言を初期状態に戻します。よろしいですか？');">
      <?php wp_nonce_field('zexter_save_settings', 'zexter_settings_nonce'); ?>
      <input type="hidden" name="zexter_reset_defaults" value="1" />
      <button type="submit" class="button">初期文言にリセット</button>
    </form>
  </div>
  <?php
}
