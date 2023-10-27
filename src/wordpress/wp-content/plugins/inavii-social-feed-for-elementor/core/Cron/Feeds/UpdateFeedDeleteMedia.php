<?php

namespace Inavii\Instagram\Cron\Feeds;

use Inavii\Instagram\PostTypes\Account\Account;
use Inavii\Instagram\PostTypes\Account\AccountPostType;
use Inavii\Instagram\PostTypes\Feed\FeedPostType;
use Inavii\Instagram\Utils\CompareMedia;

class UpdateFeedDeleteMedia
{
    private $account;
    private $accountPostType;
    private $feedPostType;

    public function __construct(Account $account)
    {
        $this->account         = $account;
        $this->accountPostType = new AccountPostType();
        $this->feedPostType    = new FeedPostType();
    }

    public function update(): void
    {
        $this->getRelationFeeds();
    }

    private function getRelationFeeds(): void
    {
        $feedsIds     = $this->feedPostType->getRelatedFeedsIds($this->account->wpAccountID());
        $accountMedia = $this->accountPostType->getMedia($this->account->wpAccountID());

        foreach ($feedsIds as $id) {
            $feedMedia = $this->feedPostType->get($id, false);

            $this->comapreMedia($accountMedia, $feedMedia, $id);
        }
    }

    private function comapreMedia($accountMedia, $feedsMedia, $feedId): void
    {
        $diff = new CompareMedia($accountMedia, $feedsMedia);

        if ($diff->postsToBeDeleted()) {
            $results = $this->removeElementFromArray($feedsMedia, $diff->postsToBeDeleted());

            $this->feedPostType->updateMedia($feedId, $results);
        }
    }

    private function removeElementFromArray($actualMedia, $deleteMedia): array
    {
        return array_filter($actualMedia, static function ($item) use ($deleteMedia) {
            return ! in_array($item, $deleteMedia, true);
        });
    }
}