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
      <h1 class="heading reveal reveal-delay-1">事業内容</h1>
      <p class="lead reveal reveal-delay-2"><?php zexter_e('services_lead'); ?></p>
    </div>
  </section>

  <section class="section section--tight">
    <div class="wrap">
      <div class="service-detail">
        <?php foreach ($services as $i => $svc) :
          $tags = preg_split('/\r\n|\r|\n/', $svc['tags']) ?: [];
          $tags = array_values(array_filter(array_map('trim', $tags)));
          ?>
          <article class="service-panel reveal" id="<?php echo esc_attr($svc['id']); ?>">
            <button class="service-panel__trigger" type="button" aria-expanded="false">
              <span class="service-panel__num"><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
              <span class="service-panel__title"><?php echo esc_html($svc['title']); ?></span>
              <span class="service-panel__icon" aria-hidden="true"></span>
            </button>
            <div class="service-panel__body">
              <div class="service-panel__inner">
                <div class="service-panel__content">
                  <p><?php echo nl2br(esc_html($svc['body']), false); ?></p>
                  <?php if ($tags) : ?>
                    <div class="service-panel__tags">
                      <?php foreach ($tags as $tag) : ?>
                        <span><?php echo esc_html($tag); ?></span>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="guide reveal" style="margin-top:3.5rem">
        <h3>事業を追加するとき</h3>
        <div class="guide-steps">
          <div>
            <strong>管理画面で1件増やす</strong>
            <p>「外観 → Zexter設定 → 事業内容」で空の枠にタイトルを入れると一覧に追加されます。</p>
          </div>
          <div>
            <strong>ホームにも自動反映</strong>
            <p>トップの軌道・カード一覧にも同じ内容が反映されます。</p>
          </div>
          <div>
            <strong>お問い合わせの選択肢も更新</strong>
            <p>フォームの「ご相談分野」にも自動で並びます。</p>
          </div>
        </div>
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
