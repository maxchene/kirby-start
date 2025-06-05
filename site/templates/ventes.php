<?php
/**
 * @var \Kirby\Cms\Page $page
 */
$ventes = $page->children();
?>
<?= snippet('header') ?>
<h1><?= $page->title(); ?></h1>
<?php foreach ($ventes as $vente): ?>
  <?= snippet('cards/vente', ['vente' => $vente]); ?>
  <a href="<?= $vente->url(); ?>"><?= $vente->title(); ?></a>

<?php endforeach; ?>

<?= snippet('footer') ?>
