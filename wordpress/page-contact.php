<?php
/**
 * Template Name: お問い合わせ
 */
get_header();
$services = zexter_services();
$cf7 = trim(zexter_get('contact_cf7'));
?>
<main class="main">
  <section class="page-hero">
    <div class="page-hero__bg" aria-hidden="true"></div>
    <div class="wrap">
      <p class="eyebrow reveal">CONTACT</p>
      <h1 class="heading reveal reveal-delay-1">お問い合わせ</h1>
      <p class="lead reveal reveal-delay-2"><?php zexter_e('contact_lead'); ?></p>
    </div>
  </section>

  <section class="section section--tight">
    <div class="wrap contact-layout">
      <div>
        <p class="eyebrow reveal">BEFORE YOU SEND</p>
        <h2 class="heading reveal reveal-delay-1" style="font-size:clamp(1.7rem,3.5vw,2.3rem)"><?php zexter_e('contact_guide_heading'); ?></h2>
        <p class="contact-note reveal reveal-delay-2" style="margin-top:1.25rem"><?php zexter_e('contact_guide_note'); ?></p>
        <ul class="contact-channels reveal">
          <li>
            <small>EMAIL</small>
            <strong><?php zexter_e('contact_email', false); ?></strong>
            <p style="margin-top:0.35rem;font-size:0.85rem;color:var(--ink-mute)"><?php zexter_e('contact_email_note'); ?></p>
          </li>
          <li>
            <small>PHONE</small>
            <strong><?php zexter_e('contact_phone', false); ?></strong>
            <p style="margin-top:0.35rem;font-size:0.85rem;color:var(--ink-mute)"><?php zexter_e('contact_phone_note'); ?></p>
          </li>
          <li>
            <small>HOURS</small>
            <strong><?php zexter_e('contact_hours', false); ?></strong>
            <p style="margin-top:0.35rem;font-size:0.85rem;color:var(--ink-mute)"><?php zexter_e('contact_hours_note'); ?></p>
          </li>
        </ul>
      </div>

      <div class="reveal reveal-delay-2">
        <?php if ($cf7 !== '') : ?>
          <div class="form zexter-cf7">
            <?php echo do_shortcode($cf7); ?>
          </div>
        <?php else : ?>
          <form class="form" id="contact-form" novalidate>
            <div class="form-field">
              <label for="name">お名前 *</label>
              <input id="name" name="name" type="text" autocomplete="name" required placeholder="山田 太郎" />
            </div>
            <div class="form-field">
              <label for="email">メールアドレス *</label>
              <input id="email" name="email" type="email" autocomplete="email" required placeholder="example@email.com" />
            </div>
            <div class="form-field">
              <label for="tel">電話番号</label>
              <input id="tel" name="tel" type="tel" autocomplete="tel" placeholder="090-0000-0000" />
            </div>
            <div class="form-field">
              <label for="topic">ご相談分野 *</label>
              <select id="topic" name="topic" required>
                <option value="">選択してください</option>
                <?php foreach ($services as $svc) : ?>
                  <option value="<?php echo esc_attr($svc['id']); ?>"><?php echo esc_html($svc['title']); ?></option>
                <?php endforeach; ?>
                <option value="other">その他・分からない</option>
              </select>
            </div>
            <div class="form-field">
              <label for="message">お問い合わせ内容 *</label>
              <textarea id="message" name="message" required placeholder="例：近所の鳥の糞被害で困っています。見積もりを希望します。"></textarea>
            </div>
            <p class="form-status" role="status" aria-live="polite"></p>
            <button class="btn btn--solid" type="submit">内容を送る <span class="btn__arrow" aria-hidden="true">→</span></button>
            <p style="margin-top:0.75rem;font-size:0.8rem;color:var(--ink-mute)"><?php zexter_e('contact_form_note'); ?></p>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
