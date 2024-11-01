<?php

namespace iutnc\deefy\render;

/**
 * Interface Renderer est une interface qui permet de rendre un objet
 */
interface Renderer
{
    const COMPACT = 1;
    const LONG = 2;
    public function render(int $type): string;
}

