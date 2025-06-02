<?php
$ventes = $page->children();
?>
<?= snippet('header') ?>
<?php foreach ($ventes as $vente): ?>
  <a href="<?= $vente->url(); ?>"><?= $vente->title(); ?></a>

<?php endforeach; ?>

<?= snippet('footer') ?>
