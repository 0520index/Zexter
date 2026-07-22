<?php
/**
 * ブロックパターン（管理画面から同じ雰囲気の塊を挿入）
 */
if (!defined('ABSPATH')) {
  exit;
}

add_action('init', function () {
  register_block_pattern_category('zexter', [
    'label' => 'Zexter',
  ]);

  register_block_pattern('zexter/intro-two-col', [
    'title' => '紹介（2カラム）',
    'categories' => ['zexter'],
    'content' => '<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">ABOUT</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">見出しをここに</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>紹介文をここに書きます。改行や段落を増やして大丈夫です。</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">DETAIL</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">詳細見出し</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>右側の説明文です。</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->',
  ]);

  register_block_pattern('zexter/info-table', [
    'title' => '概要テーブル',
    'categories' => ['zexter'],
    'content' => '<!-- wp:table {"className":"info-table"} -->
<figure class="wp-block-table info-table"><table><tbody><tr><th>項目</th><td>内容</td></tr><tr><th>項目</th><td>内容</td></tr><tr><th>項目</th><td>内容</td></tr></tbody></table></figure>
<!-- /wp:table -->',
  ]);

  register_block_pattern('zexter/timeline', [
    'title' => '沿革リスト',
    'categories' => ['zexter'],
    'content' => '<!-- wp:list {"className":"timeline"} -->
<ul class="timeline"><!-- wp:list-item -->
<li><strong>見出し</strong><br>説明文を書きます。</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>見出し</strong><br>説明文を書きます。</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>見出し</strong><br>説明文を書きます。</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->',
  ]);

  register_block_pattern('zexter/cta-buttons', [
    'title' => 'ボタン（2つ）',
    'categories' => ['zexter'],
    'content' => '<!-- wp:buttons {"className":"btn-group"} -->
<div class="wp-block-buttons btn-group"><!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="/services/">事業を見る</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/contact/">相談する</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->',
  ]);

  register_block_pattern('zexter/guide-steps', [
    'title' => '3ステップ案内',
    'categories' => ['zexter'],
    'content' => '<!-- wp:group {"className":"guide"} -->
<div class="wp-block-group guide"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">ご利用の流れ</h3>
<!-- /wp:heading -->

<!-- wp:columns {"className":"guide-steps"} -->
<div class="wp-block-columns guide-steps"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p><strong>1. タイトル</strong><br>説明文</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p><strong>2. タイトル</strong><br>説明文</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p><strong>3. タイトル</strong><br>説明文</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
  ]);
});
