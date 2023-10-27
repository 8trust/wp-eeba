<?php

namespace Inavii\Instagram\Utils;

class CompareMedia
{
    private $newMedia;
    private $oldMedia;

    public function __construct($newMedia, $oldMedia)
    {
        $this->newMedia = $newMedia;
        $this->oldMedia = $oldMedia;
    }

    public function postsToBeAdded(): array
    {
        $oldMedia = array_column($this->oldMedia, 'id');
        $results  = [];

        foreach ($this->newMedia as $media) {
            if ( ! in_array($media['id'], $oldMedia, true)) {
                $results[] = $media;
            }
        }

        return $results;
    }

    public function postsToBeDeleted(): array
    {
        $newMedia = array_column($this->newMedia, 'id');
        $results  = [];

        foreach ($this->oldMedia as $media) {
            if ( ! in_array($media['id'], $newMedia, true)) {
                $results[] = $media;
            }
        }

        return $results;
    }
}
