<?php
/**
 * @var \Kirby\Cms\Site $site
 */
?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <?php if ($site->isDev()): ?>
    <script type="module" src="http://localhost:3000/@vite/client"></script>
  <?php endif; ?>
  <?= css('css/app.scss'); ?>
  <?= js('js/app.js', ['defer' => true, 'type' => 'module']); ?>
</head>
<body>
<nav class="container">
  <a href="<?= $site->url(); ?>">Logo</a>
  <?php foreach ($site->menu()->toStructure() as $item): ?>
    <a href="<?= $item->page()->toPage()->url(); ?>"><?= $item->name(); ?></a>
  <?php endforeach; ?>
</nav>