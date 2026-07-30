  <footer class="site-footer">
    <div class="footer-grid">
      <div class="footer-brand">
        ZEXTER
        <p><?php zexter_e('footer_blurb', false); ?></p>
      </div>
      <div class="footer-nav">
        <h4>MENU</h4>
        <?php zexter_nav('primary'); ?>
      </div>
      <div class="footer-meta">
        <h4>CONTACT</h4>
        <p>まずはお気軽にご相談ください。<br />詳細はお問い合わせページへ。</p>
      </div>
    </div>
    <div class="footer-copy">
      <span>© <?php zexter_e('logo_sub', false); ?></span>
      <span><?php zexter_e('footer_tagline', false); ?></span>
    </div>
  </footer>

  <?php wp_footer(); ?>
</body>

</html>
