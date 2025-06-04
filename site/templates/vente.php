<?php
/**
 * @var VentePage $page
 */

$galleryImages = $page->images()->template('image');
$cover = $page->cover();
?>

<?= snippet('header'); ?>
  <div class="vente-gallery">
    <img src="<?= $cover->url(); ?>" alt="<?= $page->title() ?>" loading="lazy">
  </div>
  <div class="vente-grid">

  </div>
  <h1><?= $page->title(); ?></h1>

  <!--<img src="<?= $page->agent()->toUser()->avatar()->url() ?>" alt="Bastian's avatar">-->

<?= $page->description()->kt(); ?>

<?= snippet('footer'); ?>