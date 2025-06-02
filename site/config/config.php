<?php

return [
    'url' => 'http://localhost:8000',
    'panel' => [
        'css' => 'assets/css/panel.css'
    ],
    'thumbs' => require_once('thumbs.php'),
    'email' => require_once('email.php'),
    'types' => [
        'appartement' => 'Appartement',
        'maison' => 'Maison',
        'immeuble' => 'Immeuble',
        'terrain' => 'Terrain',
        "stationnement" => 'Stationnement',
        'enemble' => 'Emsemble immobilier'
    ],
];