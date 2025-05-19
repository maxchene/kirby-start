<?php

function icon(string $icon): string
{
    return '<svg class="icon"><use href="/public/sprite.svg#'.$icon.'"></use></svg>';
}