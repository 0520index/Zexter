<?php
/**
 * Template Name: 会社情報
 */
get_header();
$rows = zexter_company_rows();
$timeline = zexter_pairs('about_timeline');
?>
<main class="main">
  <section class="page-hero">
    <div class="page-hero__bg" aria-hidden="true"></div>
    <div class="wrap">
      <p class="eyebrow reveal">ABOUT</p>
      <h1 class="heading reveal reveal-delay-1"><?php the_title(); ?></h1>
      <p class="lead reveal reveal-delay-2"><?php zexter_e('about_lead'); ?></p>
    </div>
  </section>

  <section class="section section--tight">
    <div class="wrap about-grid">
      <div>
        <p class="eyebrow reveal">WHO WE ARE</p>
        <h2 class="heading reveal reveal-delay-1" style="font-size:clamp(1.8rem,4vw,2.6rem)"><?php zexter_e('about_who_heading'); ?></h2>
        <div class="divider reveal"></div>
        <div class="lead reveal" style="margin-top:0"><?php zexter_e_paras('about_who_body'); ?></div>
      </div>
      <div class="reveal reveal-delay-2">
        <p class="eyebrow">COMPANY</p>
        <h2 class="heading" style="font-size:1.8rem">会社概要</h2>
        <table class="info-table">
          <tbody>
            <?php foreach ($rows as $row) : ?>
              <tr>
                <th><?php echo esc_html($row['label']); ?></th>
                <td>
                  <?php if (trim($row['value']) === '__contact_link__') : ?>
                    <a href="<?php echo esc_url(zexter_page_url('contact')); ?>" style="color:var(--gold-deep);border-bottom:1px solid var(--line)">お問い合わせページへ</a>
                  <?php else : ?>
                    <?php echo nl2br(esc_html($row['value']), false); ?>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <p class="eyebrow reveal">HISTORY</p>
      <h2 class="heading reveal reveal-delay-1"><?php zexter_e('about_timeline_heading', false); ?></h2>
      <p class="lead reveal reveal-delay-2"><?php zexter_e('about_timeline_lead'); ?></p>
      <ul class="timeline">
        <?php foreach ($timeline as $i => $item) :
          $delay = $i % 3;
          $delay_class = $delay === 1 ? ' reveal-delay-1' : ($delay === 2 ? ' reveal-delay-2' : '');
          ?>
          <li class="reveal<?php echo esc_attr($delay_class); ?>">
            <strong><?php echo esc_html($item['title']); ?></strong>
            <p><?php echo esc_html($item['text']); ?></p>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="btn-group reveal">
        <a class="btn btn--solid" href="<?php echo esc_url(zexter_page_url('services')); ?>">事業内容を見る <span class="btn__arrow" aria-hidden="true">→</span></a>
        <a class="btn" href="<?php echo esc_url(zexter_page_url('contact')); ?>">相談する</a>
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
