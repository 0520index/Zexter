<?php
/**
 * お知らせ詳細
 */
get_header();

while (have_posts()) {
  the_post();
  ?>
<main class="main">
  <section class="page-hero">
    <div class="page-hero__bg" aria-hidden="true"></div>
    <div class="wrap">
      <p class="eyebrow reveal">NEWS</p>
      <h1 class="heading reveal reveal-delay-1"><?php the_title(); ?></h1>
      <p class="lead reveal reveal-delay-2">
        <time datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time>
        · <?php echo esc_html(zexter_news_category_label(get_the_ID())); ?>
      </p>
    </div>
  </section>

  <section class="section section--tight">
    <div class="wrap wrap--narrow">
      <article class="news-feed__item reveal" style="list-style:none">
        <div class="entry-content news-feed__body">
          <?php the_content(); ?>
        </div>
      </article>
      <div class="btn-group reveal" style="margin-top:2.5rem">
        <a class="btn" href="<?php echo esc_url(zexter_news_url()); ?>">一覧へ戻る <span class="btn__arrow" aria-hidden="true">→</span></a>
        <a class="btn btn--solid" href="<?php echo esc_url(zexter_page_url('contact')); ?>">お問い合わせ</a>
      </div>
    </div>
  </section>
</main>
  <?php
}

get_footer();
