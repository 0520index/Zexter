<?php
/**
 * Default page template — 管理画面の本文を Zexter デザインで表示
 * Template Name: Zexter標準ページ
 */
get_header();

while (have_posts()) {
  the_post();
  $eyebrow = get_post_meta(get_the_ID(), 'zexter_eyebrow', true);
  if (!is_string($eyebrow) || $eyebrow === '') {
    $eyebrow = 'PAGE';
  }
  ?>
<main class="main">
  <section class="page-hero">
    <div class="page-hero__bg" aria-hidden="true"></div>
    <div class="wrap">
      <p class="eyebrow reveal"><?php echo esc_html($eyebrow); ?></p>
      <h1 class="heading reveal reveal-delay-1"><?php the_title(); ?></h1>
      <?php if (has_excerpt()) : ?>
        <p class="lead reveal reveal-delay-2"><?php echo esc_html(get_the_excerpt()); ?></p>
      <?php endif; ?>
    </div>
  </section>

  <section class="section section--tight">
    <div class="wrap">
      <div class="entry-content reveal">
        <?php the_content(); ?>
      </div>
    </div>
  </section>
</main>
  <?php
}

get_footer();
