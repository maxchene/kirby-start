<?= snippet('header'); ?>
  <h1><?= $page->title(); ?></h1>

  <img src="<?= $page->agent()->toUser()->avatar()->url() ?>" alt="Bastian's avatar">

<?= snippet('footer'); ?>