<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php
  $desc = '';
  if (is_front_page()) {
    $desc = zexter_get('home_meta_description');
  } elseif (is_page('about') || is_page_template('page-about.php')) {
    $desc = zexter_get('about_meta_description');
  } elseif (is_page('services') || is_page_template('page-services.php')) {
    $desc = zexter_get('services_meta_description');
  } elseif (is_page('contact') || is_page_template('page-contact.php')) {
    $desc = zexter_get('contact_meta_description');
  } elseif (is_post_type_archive('zexter_news') || is_singular('zexter_news')) {
    $desc = zexter_get('news_meta_description');
  }
  if ($desc !== '') :
  ?>
    <meta name="description" content="<?php echo esc_attr($desc); ?>" />
  <?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
  <div class="atmosphere" aria-hidden="true"></div>
  <div class="fx-field" aria-hidden="true">
    <span class="fx-field__slash fx-field__slash--a"></span>
    <span class="fx-field__slash fx-field__slash--b"></span>
    <span class="fx-field__slash fx-field__slash--c"></span>
  </div>
  <div class="scroll-progress" aria-hidden="true"></div>

  <?php if (is_front_page()) : ?>
  <div class="intro" aria-hidden="true">
    <div class="intro__mark">
      <div class="intro__brand">ZEXTER</div>
      <div class="intro__line"></div>
      <p class="intro__sub">BUILT TO MOVE</p>
    </div>
    <div class="intro__loader">
      <div class="intro__percent"><span data-intro-percent>0</span><span class="intro__percent-unit">%</span></div>
      <div class="intro__bar" aria-hidden="true">
        <div class="intro__bar-fill" data-intro-bar></div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <header class="site-header">
    <a class="logo" href="<?php echo esc_url(home_url('/')); ?>">ZEXTER<span><?php zexter_e('logo_sub', false); ?></span></a>
    <button class="nav-toggle" type="button" aria-label="メニュー" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
    <nav class="nav" aria-label="メインメニュー">
      <?php zexter_nav('primary'); ?>
    </nav>
  </header>
