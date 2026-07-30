<?php
/**
 * お知らせ一覧
 */
get_header();
?>
<main class="main">
  <section class="page-hero">
    <div class="page-hero__bg" aria-hidden="true"></div>
    <div class="wrap">
      <p class="eyebrow reveal">NEWS</p>
      <h1 class="heading reveal reveal-delay-1">お知らせ</h1>
      <p class="lead reveal reveal-delay-2"><?php zexter_e('news_lead'); ?></p>
    </div>
  </section>

  <section class="section section--tight">
    <div class="wrap wrap--narrow">
      <?php if (have_posts()) : ?>
        <ul class="news-feed">
          <?php
          $i = 0;
          while (have_posts()) :
            the_post();
            $delay = $i % 3;
            $delay_class = $delay === 1 ? ' reveal-delay-1' : ($delay === 2 ? ' reveal-delay-2' : '');
            ?>
            <li id="news-<?php echo esc_attr(get_the_ID()); ?>" class="news-feed__item reveal<?php echo esc_attr($delay_class); ?>">
              <div class="news-feed__meta">
                <time datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time>
                <span class="news-feed__cat"><?php echo esc_html(zexter_news_category_label(get_the_ID())); ?></span>
              </div>
              <h2 class="news-feed__title">
                <a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none"><?php the_title(); ?></a>
              </h2>
              <div class="news-feed__body">
                <?php
                if (has_excerpt()) {
                  the_excerpt();
                } else {
                  echo wp_kses_post(wpautop(wp_trim_words(wp_strip_all_tags(get_the_content()), 80, '…')));
                }
                ?>
              </div>
            </li>
            <?php
            $i++;
          endwhile;
          ?>
        </ul>
        <div class="btn-group reveal" style="margin-top:2rem">
          <?php the_posts_pagination([
            'mid_size' => 1,
            'prev_text' => '← 前へ',
            'next_text' => '次へ →',
          ]); ?>
        </div>
      <?php else : ?>
        <p class="lead reveal">お知らせはまだありません。</p>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php
get_footer();
