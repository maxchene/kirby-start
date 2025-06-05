<?php

use Kirby\Cms\App;

function icon(string $icon): string
{
    return '<svg class="icon"><use href="/public/sprite.svg#' . $icon . '"></use></svg>';
}

function toStructuredDataScript(array $data): string
{
    $jsonLd = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return "<script type=\"application/ld+json\">\n{$jsonLd}\n</script>";
}


App::plugin('maxchene/seo', [
    'pageMethods' => [
        'seoTitle' => function () {
            $default = "Bruno Paquet Immobilier à Poitiers : maisons, appartements, terrains à vendre";
            return match (true) {
                $this->intendedTemplate()->name() === 'vente' => "{$this->title()}, {$this->type()} {$this->secteur()} - {$this->site()->title()}",
                $this->isHomePage() => $default,
                default => "{$this->title()} - {$this->site()->title()}",
            };
        },
        'structuredData' => function () {
            $page = $this;
            $site = $page->site();
            $logoUrl = $site->url() . '/assets/img/logo.png';

            // Valeurs communes
            $base = [
                '@context' => 'https://schema.org',
            ];

            switch ($page->intendedTemplate()->name()) {
                case 'home':
                    $data = array_merge($base, [
                        '@type' => 'WebSite',
                        'name' => $site->title()->value(),
                        'url' => $site->url(),
                        'publisher' => [
                            '@type' => 'Organization',
                            'name' => 'Bruno Paquet Immobilier',
                            'url' => $site->url(),
                            'logo' => [
                                '@type' => 'ImageObject',
                                'url' => $logoUrl
                            ]
                        ]
                    ]);
                    break;

                case 'vente':
                case 'location':
                    $data = array_merge($base, [
                        '@type' => 'Offer',
                        'name' => $page->title()->value(),
                        'description' => $page->description()->excerpt(160)->value(),
                        'url' => $page->url(),
                        'priceCurrency' => 'EUR',
                        'price' => $page->prix()->value(),
                        'availability' => $page->intendedTemplate()->name() === 'vente'
                            ? 'https://schema.org/InStock'
                            : 'https://schema.org/PreOrder',
                        'itemOffered' => [
                            '@type' => 'SingleFamilyResidence',
                            'address' => [
                                '@type' => 'PostalAddress',
                                'addressLocality' => $page->ville()->value(),
                                'postalCode' => $page->codepostal()->value(),
                                'addressCountry' => 'FR'
                            ],
                            'numberOfRooms' => intval($page->pieces()->value()),
                            'floorSize' => [
                                '@type' => 'QuantitativeValue',
                                'value' => floatval($page->surface()->value()),
                                'unitCode' => 'MTK'
                            ]
                        ],
                        'seller' => [
                            '@type' => 'RealEstateAgent',
                            'name' => 'Bruno Paquet Immobilier',
                            'url' => $site->url()
                        ]
                    ]);
                    break;

                case 'contact':
                    $data = array_merge($base, [
                        '@type' => 'ContactPage',
                        'name' => $page->title()->value(),
                        'url' => $page->url()
                    ]);
                    break;

                case 'agence':
                    $data = array_merge($base, [
                        '@type' => 'RealEstateAgent',
                        'name' => 'Bruno Paquet Immobilier',
                        'url' => $site->url(),
                        'image' => $logoUrl,
                        'location' => [
                            '@type' => 'Place',
                            'name' => 'Bruno Paquet Immobilier - Agence',
                            'hasMap' => 'https://maps.app.goo.gl/JrLLHmGEC9gs34bk6',
                            'address' => [
                                '@type' => 'PostalAddress',
                                'streetAddress' => '42 rue de la Marne',
                                'addressLocality' => 'Poitiers',
                                'postalCode' => '86000',
                                'addressCountry' => 'FR'
                            ],
                            'geo' => [
                                '@type' => 'GeoCoordinates',
                                'latitude' => 46.58177202862756,
                                'longitude' => 0.3384283980807309
                            ]
                        ],
                        'telephone' => '+33549469399',
                        'openingHours' => [
                            'Mo-Fr 09:00-12:30',
                            'Mo-Fr 14:00-18:30',
                            'Sa 09:30-12:00'
                        ],
                        'sameAs' => [
                            'https://www.facebook.com/brunopaquetimmobilier',
                            'https://maps.app.goo.gl/JrLLHmGEC9gs34bk6',
                            'https://www.linkedin.com/company/bruno-paquet-immobilier',
                            'https://www.instagram.com/brunopaquetimmobilier'
                        ]
                    ]);
                    break;

                default:
                    $data = array_merge($base, [
                        '@type' => 'Organization',
                        'name' => 'Bruno Paquet Immobilier',
                        'url' => $site->url(),
                        'logo' => $site->url() . '/logo.png'
                    ]);
                    break;
            }

            $jsonLd = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return "<script type=\"application/ld+json\">\n{$jsonLd}\n</script>";
        }
    ],
    'fileMethods' => [
        'lazy' => function ($preset, $alt = '') {
            $thumb = $this->thumb($preset);
            $w = $thumb->width();
            $h = $thumb->height();
            return "<img src='{$thumb->url()}' alt='{$alt}' loading='lazy' width='{$w}' height='{$h}'/>";
        }
    ]
]);