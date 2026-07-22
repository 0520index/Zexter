<?php
/**
 * Fallback template
 */
get_header();
?>
<main class="main">
  <section class="page-hero">
    <div class="page-hero__bg" aria-hidden="true"></div>
    <div class="wrap">
      <h1 class="heading"><?php the_title(); ?></h1>
    </div>
  </section>
  <section class="section section--tight">
    <div class="wrap">
      <?php
      if (have_posts()) {
        while (have_posts()) {
          the_post();
          the_content();
        }
      }
      ?>
    </div>
  </section>
</main>
<?php
get_footer();
