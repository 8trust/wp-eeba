<?php

namespace Inavii\Instagram\RestApi\EndPoints\Media;

use Inavii\Instagram\Media\DownloadRemoteMedia;
use Inavii\Instagram\Media\GenerateThumbnails;
use Inavii\Instagram\Media\Media;
use WP_Background_Process;

class ImporterMediaProcessor extends WP_Background_Process
{
    protected $action = 'importer-instagram-media';
    private $downloadRemoteMedia;

    private $thumbnails;

    public function __construct()
    {
        parent::__construct();
        $this->downloadRemoteMedia = new DownloadRemoteMedia();
        $this->thumbnails = new GenerateThumbnails();
    }

    protected function task($item): bool
    {
        $this->downloadRemoteMedia->save($item['url'], $item['id']);
        $this->thumbnails->generate($item['id'], Media::IMAGE_MEDIUM);

        if (!empty($item['children'])) {
            $this->importCarouselAlbum($item['children']);
        }

        return false;
    }

    private function importCarouselAlbum($children): void
    {
        foreach ($children as $child) {
            $this->downloadRemoteMedia->save($child['url'], $child['id']);
            $this->thumbnails->generate($child['id'], Media::IMAGE_MEDIUM);
        }
    }

    protected function complete(): void
    {
        parent::complete();
        $generateMediaProcessor = new GenerateThumbnailsProcessor();
        $generateMediaProcessor->dispatch();
    }
}