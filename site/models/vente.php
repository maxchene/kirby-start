<?php


use Kirby\Cms\Page;

class VentePage extends Page
{

    public function cover()
    {
        return $this->images()->sortBy('sort')->first();
    }
}
