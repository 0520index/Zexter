<?php
/**
 * Front page (ホーム)
 */
get_header();

$services = zexter_services();
$angles = zexter_orbit_angles(count($services));
$icons = [
  'pest' => '<circle cx="20" cy="20" r="8" stroke="currentColor" stroke-width="1.5" /><path d="M20 4v4M20 32v4M4 20h4M32 20h4M8.5 8.5l2.8 2.8M28.7 28.7l2.8 2.8M8.5 31.5l2.8-2.8M28.7 11.3l2.8-2.8" stroke="currentColor" stroke-width="1.5" />',
  'electric' => '<path d="M22 4L10 22h10l-2 14 12-18H20l2-14z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />',
  'cleaning' => '<path d="M8 28c4-8 8-12 12-12s8 4 12 12" stroke="currentColor" stroke-width="1.5" /><path d="M12 18c2-4 4-6 8-6s6 2 8 6" stroke="currentColor" stroke-width="1.5" /><circle cx="20" cy="10" r="2" fill="currentColor" />',
  'buyout' => '<rect x="8" y="14" width="24" height="16" stroke="currentColor" stroke-width="1.5" /><path d="M14 14V12a6 6 0 0 1 12 0v2" stroke="currentColor" stroke-width="1.5" /><circle cx="20" cy="22" r="2.5" stroke="currentColor" stroke-width="1.5" />',
  'ad' => '<path d="M8 26V14l14-6v24L8 26z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" /><path d="M22 16h6a4 4 0 0 1 0 8h-6" stroke="currentColor" stroke-width="1.5" />',
];
$fallback_icon = '<circle cx="20" cy="20" r="7" stroke="currentColor" stroke-width="1.5" />';
?>
<main class="main">
  <section class="hero">
    <div class="hero__plane" aria-hidden="true"></div>
    <div class="hero__shine" aria-hidden="true"></div>
    <div class="hero__slash" aria-hidden="true"></div>
    <div class="hero__content">
      <p class="hero__meta"><?php zexter_e('home_hero_meta', false); ?></p>
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
      <div class="philosophy__visual reveal" aria-hidden="true"></div>
      <div>
        <p class="eyebrow reveal">PHILOSOPHY</p>
        <h2 class="heading reveal reveal-delay-1"><?php zexter_e_gold_heading('home_philosophy_heading'); ?></h2>
        <div class="divider reveal reveal-delay-2"></div>
        <p class="lead reveal reveal-delay-2" style="margin-top:0"><?php zexter_e('home_philosophy_lead'); ?></p>
        <ul class="philosophy__list">
          <li class="reveal">
            <span class="philosophy__num">01</span>
            <div>
              <strong><?php zexter_e('home_phil_1_title', false); ?></strong>
              <p><?php zexter_e('home_phil_1_text'); ?></p>
            </div>
          </li>
          <li class="reveal reveal-delay-1">
            <span class="philosophy__num">02</span>
            <div>
              <strong><?php zexter_e('home_phil_2_title', false); ?></strong>
              <p><?php zexter_e('home_phil_2_text'); ?></p>
            </div>
          </li>
          <li class="reveal reveal-delay-2">
            <span class="philosophy__num">03</span>
            <div>
              <strong><?php zexter_e('home_phil_3_title', false); ?></strong>
              <p><?php zexter_e('home_phil_3_text'); ?></p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <p class="eyebrow reveal">AT A GLANCE</p>
      <h2 class="heading reveal reveal-delay-1"><?php zexter_e('home_glance_heading'); ?></h2>
      <div class="service-grid">
        <?php foreach ($services as $i => $svc) :
          $delay = $i % 3;
          $delay_class = $delay === 1 ? ' reveal-delay-1' : ($delay === 2 ? ' reveal-delay-2' : '');
          ?>
          <a class="service-tile reveal<?php echo esc_attr($delay_class); ?>" href="<?php echo esc_url(zexter_page_url('services') . '#' . $svc['id']); ?>">
            <div>
              <span class="service-tile__num"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
              <h3><?php echo esc_html($svc['label'] !== '' ? $svc['label'] : $svc['title']); ?></h3>
              <p><?php echo esc_html($svc['short']); ?></p>
            </div>
            <span class="service-tile__link">詳しく見る →</span>
          </a>
        <?php endforeach; ?>
        <a class="service-tile reveal reveal-delay-2">
          <div>
            <span class="service-tile__num">∞</span>
            <h3><?php zexter_e('home_coming_title', false); ?></h3>
            <p><?php zexter_e('home_coming_text'); ?></p>
          </div>
          <span class="service-tile__link"><?php zexter_e('home_coming_title', false); ?></span>
        </a>
      </div>

      <div class="guide reveal">
        <h3><?php zexter_e('home_guide_title', false); ?></h3>
        <div class="guide-steps">
          <div>
            <strong><?php zexter_e('home_guide_1_title', false); ?></strong>
            <p><?php zexter_e('home_guide_1_text'); ?></p>
          </div>
          <div>
            <strong><?php zexter_e('home_guide_2_title', false); ?></strong>
            <p><?php zexter_e('home_guide_2_text'); ?></p>
          </div>
          <div>
            <strong><?php zexter_e('home_guide_3_title', false); ?></strong>
            <p><?php zexter_e('home_guide_3_text'); ?></p>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
