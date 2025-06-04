<?php

use Kirby\Cms\App;

App::plugin('maxchene/seo', [
    'pageMethods' => [
        'seoTitle' => function () {
            $default = "Bruno Paquet Immobilier à Poitiers : maisons, appartements, terrains à vendre";
            return match (true) {
                $this->intendedTemplate()->name() === 'vente' => "{$this->title()}, {$this->type()} {$this->secteur()} | {$this->site()->title()}",
                $this->isHomePage() => $default,
                default => "{$this->title()} | {$this->site()->title()}",
            };
        },
        'structuredData' => function () {
            $data = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => 'Mon Entreprise',
                'url' => 'https://www.monentreprise.com',
                'logo' => 'https://www.monentreprise.com/logo.png',
                'sameAs' => [
                    'https://www.facebook.com/monentreprise',
                    'https://www.instagram.com/monentreprise'
                ]
            ];
            $jsonLd = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return "<script type=\"application/ld+json\">\n{$jsonLd}\n</script>";
        }
    ]
]);