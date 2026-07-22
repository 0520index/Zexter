<?php
/**
 * Template Name: 会社情報
 */
get_header();
?>
<main class="main">
  <section class="page-hero">
    <div class="page-hero__bg" aria-hidden="true"></div>
    <div class="wrap">
      <p class="eyebrow reveal">ABOUT</p>
      <h1 class="heading reveal reveal-delay-1">会社情報</h1>
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
            <tr>
              <th>会社名</th>
              <td><?php zexter_e('company_name', false); ?></td>
            </tr>
            <tr>
              <th>設立</th>
              <td><?php zexter_e('company_founded'); ?></td>
            </tr>
            <tr>
              <th>事業内容</th>
              <td><?php zexter_e('company_business'); ?></td>
            </tr>
            <tr>
              <th>カラー</th>
              <td><?php zexter_e('company_color', false); ?></td>
            </tr>
            <tr>
              <th>所在地</th>
              <td><?php zexter_e('company_address'); ?></td>
            </tr>
            <tr>
              <th>連絡先</th>
              <td><a href="<?php echo esc_url(zexter_page_url('contact')); ?>" style="color:var(--gold-deep);border-bottom:1px solid var(--line)">お問い合わせページへ</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <p class="eyebrow reveal">STEPS</p>
      <h2 class="heading reveal reveal-delay-1">これまでの流れ</h2>
      <p class="lead reveal reveal-delay-2"><?php zexter_e('about_timeline_lead'); ?></p>
      <ul class="timeline">
        <li class="reveal">
          <strong><?php zexter_e('about_tl_1_title', false); ?></strong>
          <p><?php zexter_e('about_tl_1_text'); ?></p>
        </li>
        <li class="reveal reveal-delay-1">
          <strong><?php zexter_e('about_tl_2_title', false); ?></strong>
          <p><?php zexter_e('about_tl_2_text'); ?></p>
        </li>
        <li class="reveal reveal-delay-2">
          <strong><?php zexter_e('about_tl_3_title', false); ?></strong>
          <p><?php zexter_e('about_tl_3_text'); ?></p>
        </li>
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
