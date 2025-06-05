<?php
/**
 * @var VentePage $vente
 */
$cover = $vente->cover();
?>

<article>
  <?= $cover->lazy('card', $vente->title()); ?>
  <?= $vente->title(); ?>

</article>