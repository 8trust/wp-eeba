<?php

namespace Inavii\Instagram\RestApi\EndPoints\Media;

use Inavii\Instagram\Media\GenerateThumbnails;
use Inavii\Instagram\PostTypes\Account\AccountPostType;
use WP_Background_Process;

class GenerateThumbnailsProcessor extends WP_Background_Process
{
    protected $action = 'generate-thumbnails-media';
    private $thumbnails;
    private $accountID = 0;
    private $account;

    public function __construct()
    {
        parent::__construct();
        $this->account = new AccountPostType();
        $this->thumbnails = new GenerateThumbnails();
    }
    protected function task($item): bool
    {
        $this->thumbnails->generate($item['id']);

        if (!empty($item['children'])) {
            $this->generateCarouselAlbumThumbnails($item['children']);
        }

        return false;
    }

    private function generateCarouselAlbumThumbnails($children): bool
    {
        foreach ($children as $child) {
            $this->thumbnails->generate($child['id']);
        }

        return false;
    }

    protected function complete(): void
    {
        parent::complete();
        $this->account->setImporterMediaStatus($this->accountID, true);
    }
}