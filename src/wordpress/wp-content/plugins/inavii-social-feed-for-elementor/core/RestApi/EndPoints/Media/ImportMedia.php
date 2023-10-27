<?php

namespace Inavii\Instagram\RestApi\EndPoints\Media;

use Inavii\Instagram\PostTypes\Account\AccountPostType;

class ImportMedia
{

    private $account;

    public function __construct()
    {
        $this->account = new AccountPostType();
    }

    public function import($accountId): void
    {
        $media = $this->account->getMedia((int)$accountId);

        $importMediaProcessor = new ImporterMediaProcessor();
        $generateMediaProcessor = new GenerateThumbnailsProcessor();

        if ($media) {
            foreach ($media as $medium) {
                $importMediaProcessor->push_to_queue($medium);
                $generateMediaProcessor->push_to_queue($medium);
            }

            $importMediaProcessor->save()->dispatch();
            $generateMediaProcessor->save();
        }

    }
}