<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script type="module" src="http://localhost:3000/@vite/client"></script>
  <?= css('css/app.css'); ?>
  <?= js('js/app.js', ['defer' => true, 'type' => 'module']); ?>
</head>
<body>