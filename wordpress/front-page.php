<?php
/**
 * Front page (ホーム)
 */
get_header();

$services = zexter_services();
$angles = zexter_orbit_angles(count($services));
$philosophy = zexter_pairs('home_philosophy');
$guide = zexter_pairs('home_guide');
$news_count = max(1, (int) zexter_get('home_news_count', '3'));
$icons = [
  'pest' => '<circle cx="20" cy="20" r="8" stroke="currentColor" stroke-width="1.5" /><path d="M20 4v4M20 32v4M4 20h4M32 20h4M8.5 8.5l2.8 2.8M28.7 28.7l2.8 2.8M8.5 31.5l2.8-2.8M28.7 11.3l2.8-2.8" stroke="currentColor" stroke-width="1.5" />',
  'electric' => '<path d="M22 4L10 22h10l-2 14 12-18H20l2-14z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />',
  'cleaning' => '<path d="M8 28c4-8 8-12 12-12s8 4 12 12" stroke="currentColor" stroke-width="1.5" /><path d="M12 18c2-4 4-6 8-6s6 2 8 6" stroke="currentColor" stroke-width="1.5" /><circle cx="20" cy="10" r="2" fill="currentColor" />',
  'buyout' => '<rect x="8" y="14" width="24" height="16" stroke="currentColor" stroke-width="1.5" /><path d="M14 14V12a6 6 0 0 1 12 0v2" stroke="currentColor" stroke-width="1.5" /><circle cx="20" cy="22" r="2.5" stroke="currentColor" stroke-width="1.5" />',
  'ad' => '<path d="M8 26V14l14-6v24L8 26z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" /><path d="M22 16h6a4 4 0 0 1 0 8h-6" stroke="currentColor" stroke-width="1.5" />',
];
$fallback_icon = '<circle cx="20" cy="20" r="7" stroke="currentColor" stroke-width="1.5" />';

$news_query = new WP_Query([
  'post_type' => 'zexter_news',
  'posts_per_page' => $news_count,
  'post_status' => 'publish',
]);
?>
<main class="main">
  <section class="hero">
    <div class="hero__plane" aria-hidden="true"></div>
    <div class="hero__shine" aria-hidden="true"></div>
    <div class="hero__slash" aria-hidden="true"></div>
    <div class="hero__content">
      <p class="hero__meta"><?php
        $hero_meta = zexter_get('home_hero_meta');
        if (preg_match('/^(\S+)\s*(\/.*)?$/u', $hero_meta, $m)) {
          echo '<span>' . esc_html($m[1]) . '</span>';
          if (!empty($m[2])) {
            echo ' ' . esc_html(ltrim($m[2]));
          }
        } else {
          echo esc_html($hero_meta);
        }
      ?></p>
      <h1 class="hero__brand">ZEXTER</h1>
      <p class="hero__headline"><?php zexter_e('home_hero_headline'); ?></p>
      <p class="hero__copy"><?php zexter_e('home_hero_copy'); ?></p>
      <div class="btn-group">
        <a class="btn btn--solid" href="<?php echo esc_url(zexter_page_url('services')); ?>">事業を見る <span class="btn__arrow" aria-hidden="true">→</span></a>
        <a class="btn" href="<?php echo esc_url(zexter_page_url('contact')); ?>">相談する</a>
      </div>
    </div>
    <div class="hero__scroll" aria-hidden="true">
      <span>SCROLL</span>
      <div class="hero__scroll-line"></div>
    </div>
  </section>

  <div class="marquee" aria-hidden="true">
    <div class="marquee__track">
      <?php for ($m = 0; $m < 2; $m++) : ?>
        <?php foreach ($services as $svc) : ?>
          <span><?php echo esc_html($svc['title']); ?></span>
        <?php endforeach; ?>
      <?php endfor; ?>
    </div>
  </div>

  <section class="section section--tight">
    <div class="wrap">
      <p class="eyebrow reveal">NEWS</p>
      <h2 class="heading reveal reveal-delay-1"><?php zexter_e('home_news_heading', false); ?></h2>
      <?php if ($news_query->have_posts()) : ?>
        <ul class="news-list">
          <?php
          $i = 0;
          while ($news_query->have_posts()) :
            $news_query->the_post();
            $delay = $i % 3;
            $delay_class = $delay === 1 ? ' reveal-delay-1' : ($delay === 2 ? ' reveal-delay-2' : '');
            ?>
            <li class="news-list__item reveal<?php echo esc_attr($delay_class); ?>">
              <a class="news-list__link" href="<?php the_permalink(); ?>">
                <time datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time>
                <span class="news-list__cat"><?php echo esc_html(zexter_news_category_label(get_the_ID())); ?></span>
                <span class="news-list__title"><?php the_title(); ?></span>
              </a>
            </li>
            <?php
            $i++;
          endwhile;
          wp_reset_postdata();
          ?>
        </ul>
      <?php endif; ?>
      <div class="news-list__more reveal">
        <a class="btn" href="<?php echo esc_url(zexter_news_url()); ?>">一覧を見る <span class="btn__arrow" aria-hidden="true">→</span></a>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <p class="eyebrow reveal">BUSINESS ORBIT</p>
      <h2 class="heading reveal reveal-delay-1"><?php zexter_e('home_orbit_heading'); ?></h2>
      <p class="lead reveal reveal-delay-2"><?php zexter_e('home_orbit_lead'); ?></p>

      <div class="constellation reveal reveal-delay-3" aria-label="事業の軌道図">
        <div class="constellation__ring" aria-hidden="true"></div>
        <div class="constellation__ring constellation__ring--inner" aria-hidden="true"></div>
        <div class="constellation__center">
          <div>
            <strong>ZEXTER</strong>
            <small>多分野展開</small>
          </div>
        </div>
        <?php foreach ($services as $i => $svc) :
          $angle = $angles[$i] ?? (-90 + $i * 72);
          $icon = $icons[$svc['id']] ?? $fallback_icon;
          ?>
          <a class="orbit-node" href="<?php echo esc_url(zexter_page_url('services') . '#' . $svc['id']); ?>" style="--a:<?php echo (int) $angle; ?>deg">
            <div class="orbit-node__inner">
              <div class="orbit-node__icon" aria-hidden="true">
                <svg viewBox="0 0 40 40" fill="none"><?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed SVG paths ?></svg>
              </div>
              <div class="orbit-node__label"><?php echo esc_html($svc['label']); ?></div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section section--tight">
    <div class="wrap philosophy">
      <div class="philosophy__visual reveal">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/WordPress.png'); ?>" alt="<?php echo esc_attr(zexter_get('logo_sub')); ?>" width="256" height="256" />
      </div>
      <div>
        <p class="eyebrow reveal">PHILOSOPHY</p>
        <h2 class="heading reveal reveal-delay-1"><?php zexter_e_gold_heading('home_philosophy_heading'); ?></h2>
        <div class="divider reveal reveal-delay-2"></div>
        <p class="lead reveal reveal-delay-2" style="margin-top:0"><?php zexter_e('home_philosophy_lead'); ?></p>
        <ul class="philosophy__list">
          <?php foreach ($philosophy as $i => $item) :
            $delay = $i % 3;
            $delay_class = $delay === 1 ? ' reveal-delay-1' : ($delay === 2 ? ' reveal-delay-2' : '');
            ?>
            <li class="reveal<?php echo esc_attr($delay_class); ?>">
              <span class="philosophy__num"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
              <div>
                <strong><?php echo esc_html($item['title']); ?></strong>
                <p><?php echo esc_html($item['text']); ?></p>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <p class="eyebrow reveal">AT A GLANCE</p>
      <h2 class="heading reveal reveal-delay-1"><?php zexter_e('home_glance_heading'); ?></h2>
      <p class="lead reveal reveal-delay-2"><?php zexter_e('home_glance_lead'); ?></p>
      <div class="service-detail">
        <?php foreach ($services as $i => $svc) :
          $delay = $i % 3;
          $delay_class = $delay === 1 ? ' reveal-delay-1' : ($delay === 2 ? ' reveal-delay-2' : '');
          ?>
          <article class="service-panel reveal<?php echo esc_attr($delay_class); ?>">
            <button class="service-panel__trigger" type="button" aria-expanded="false">
              <span class="service-panel__num"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
              <span class="service-panel__title"><?php echo esc_html($svc['title']); ?></span>
              <span class="service-panel__icon" aria-hidden="true"></span>
            </button>
            <div class="service-panel__body">
              <div class="service-panel__inner">
                <div class="service-panel__content">
                  <p><?php echo esc_html($svc['short']); ?></p>
                  <a class="btn" href="<?php echo esc_url(zexter_page_url('services') . '#' . $svc['id']); ?>">詳しく見る <span class="btn__arrow" aria-hidden="true">→</span></a>
                </div>
              </div>
            </div>
          </article>
        <?php endforeach; ?>

        <article class="service-panel reveal reveal-delay-2">
          <button class="service-panel__trigger" type="button" aria-expanded="false">
            <span class="service-panel__num"><?php zexter_e('home_coming_num', false); ?></span>
            <span class="service-panel__title"><?php zexter_e('home_coming_title', false); ?></span>
            <span class="service-panel__icon" aria-hidden="true"></span>
          </button>
          <div class="service-panel__body">
            <div class="service-panel__inner">
              <div class="service-panel__content">
                <p><?php zexter_e('home_coming_text'); ?></p>
                <a class="btn" href="<?php echo esc_url(zexter_page_url('contact')); ?>">相談する <span class="btn__arrow" aria-hidden="true">→</span></a>
              </div>
            </div>
          </div>
        </article>
      </div>

      <div class="guide reveal">
        <h3><?php zexter_e('home_guide_title', false); ?></h3>
        <div class="guide-steps">
          <?php foreach ($guide as $item) : ?>
            <div>
              <strong><?php echo esc_html($item['title']); ?></strong>
              <p><?php echo esc_html($item['text']); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
