<?php

use Kirby\Cms\App as Kirby;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;

Kirby::plugin('maxchene/vite-assets', [
    'components' => [
        'css' => function (Kirby $kirby, string $url, $options = []): string {
            return uri($url);
        },
        'js' => function (Kirby $kirby, string $url, $options = []): string {
            return uri($url);
        }
    ],
]);

function uri($url): string
{
    $isDev = $_ENV['APP_ENV'] === 'dev';
    $parts = Str::split($url, '.');
    $name = $parts[0];
    $manifestFile = 'public/manifest.json';
    $manifest = json_decode((string)file_get_contents($manifestFile), true, 512, JSON_THROW_ON_ERROR);

    $found = A::find($manifest, function ($item) use ($name) {
        return $item['name'] === $name;
    });
    if (!$found) {
        return '';
    }
    return $isDev ? 'http://localhost:3000/' . $found['src'] : 'public/' . $found['file'];
}