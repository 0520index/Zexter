<?php
/**
 * Template Name: 事業内容
 */
get_header();
$services = zexter_services();
?>
<main class="main">
  <section class="page-hero">
    <div class="page-hero__bg" aria-hidden="true"></div>
    <div class="wrap">
      <p class="eyebrow reveal">SERVICES</p>
      <h1 class="heading reveal reveal-delay-1"><?php the_title(); ?></h1>
      <p class="lead reveal reveal-delay-2"><?php zexter_e('services_lead'); ?></p>
    </div>
  </section>

  <section class="section section--tight">
    <div class="wrap">
      <div class="service-grid service-grid--page">
        <?php foreach ($services as $i => $svc) :
          $tags = preg_split('/\r\n|\r|\n/', $svc['tags']) ?: [];
          $tags = array_values(array_filter(array_map('trim', $tags)));
          $delay = $i % 3;
          $delay_class = $delay === 1 ? ' reveal-delay-1' : ($delay === 2 ? ' reveal-delay-2' : '');
          ?>
          <article class="service-tile reveal<?php echo esc_attr($delay_class); ?>" id="<?php echo esc_attr($svc['id']); ?>">
            <div>
              <span class="service-tile__num"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
              <h2><?php echo esc_html($svc['title']); ?></h2>
              <p><?php echo nl2br(esc_html($svc['body']), false); ?></p>
              <?php if ($tags) : ?>
                <div class="service-panel__tags">
                  <?php foreach ($tags as $tag) : ?>
                    <span><?php echo esc_html($tag); ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
            <a class="service-tile__link" href="<?php echo esc_url(zexter_page_url('contact')); ?>">相談する →</a>
          </article>
        <?php endforeach; ?>

        <article class="service-tile service-tile--soon reveal reveal-delay-2" id="soon">
          <div>
            <span class="service-tile__num">∞</span>
            <h2><?php zexter_e('services_soon_title', false); ?></h2>
            <p><?php zexter_e('services_soon_text'); ?></p>
          </div>
          <a class="service-tile__link" href="<?php echo esc_url(zexter_page_url('contact')); ?>">相談する →</a>
        </article>
      </div>

      <div class="btn-group reveal">
        <a class="btn btn--solid" href="<?php echo esc_url(zexter_page_url('contact')); ?>">この分野で相談する <span class="btn__arrow" aria-hidden="true">→</span></a>
        <a class="btn" href="<?php echo esc_url(zexter_page_url('about')); ?>">会社情報へ</a>
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
