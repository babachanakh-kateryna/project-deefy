<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;

abstract class AudioTrackRenderer implements Renderer
{
    protected tracks\AudioTrack $audioTrack;

    public function __construct(tracks\AudioTrack $audioTrack)
    {
        $this->audioTrack = $audioTrack;
    }

    public function render(int $type): string
    {
        switch ($type) {
            case self::COMPACT:
                return $this->renderCompact() . "\n";
            case self::LONG:
                return $this->renderLong() . "\n";
            default:
                return "Type de rendu inconnu";
        }
    }

    abstract protected function renderCompact(): string;
    abstract protected function renderLong(): string;
}