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
    'siteMethods' => [
        'isDev' => function () {
            return isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'dev';
        }
    ]
]);

function uri($url): string
{
    $isDev = isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'dev';
    if ($isDev) {
        return 'http://localhost:3000/assets/' . $url;
    }
    $manifestFile = 'public/manifest.json';
    $manifest = json_decode((string)file_get_contents($manifestFile), true, 512, JSON_THROW_ON_ERROR);

    $found = A::find($manifest, function ($item, $key) use ($url) {
        return isset($item['src']) && $item['src'] === 'assets/' . $url;
    });
    if (!$found) {
        return '';
    }
    return 'public/' . $found['file'];
}