<?php
/**
 * @var \Kirby\Cms\Site $site
 * @var \Kirby\Cms\Page $page
 */
?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page->seoTitle(); ?></title>
  <?php if ($site->isDev()): ?>
    <script type="module" src="http://localhost:3000/@vite/client"></script>
  <?php endif; ?>
  <?= css('css/app.scss'); ?>
  <?= js('js/app.js', ['defer' => true, 'type' => 'module']); ?>
  <?= $page->structuredData(); ?>
</head>
<body>
<aside>
  <a href="<?= $site->url(); ?>">
    <?= snippet('logo'); ?>
  </a>
  <nav>
    <?php foreach ($site->menu()->toStructure() as $item): ?>
      <a href="<?= $item->page()->toPage()->url(); ?>"><?= $item->name(); ?></a>
    <?php endforeach; ?>
  </nav>
  <div>
    <div class="socials">
      <a href="https://www.facebook.com/brunopaquetimmobilier"
         target="_blank" title="Facebook Bruno Paquet Immobilier"><?= icon('facebook'); ?></a>
      <a href="https://www.linkedin.com/company/bruno-paquet-immobilier" title="Linkedin Bruno Paquet Immobilier"
         target="_blank"><?= icon('linkedin'); ?></a>
      <a href="https://www.instagram.com/brunopaquetimmobilier" target="_blank"
         title="Instagram Bruno Paquet Immobilier"><?= icon('instagram'); ?></a>
    </div>
  </div>
</aside>
<main>