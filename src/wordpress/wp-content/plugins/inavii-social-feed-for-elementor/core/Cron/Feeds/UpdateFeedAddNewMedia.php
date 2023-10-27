<?php

namespace Inavii\Instagram\Cron\Feeds;

use Inavii\Instagram\PostTypes\Account\Account;
use Inavii\Instagram\PostTypes\Account\AccountPostType;
use Inavii\Instagram\PostTypes\Feed\FeedPostType;

class UpdateFeedAddNewMedia
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
        $feedsIds     = $this->feedPostType->getRelatedFeedsIds($this->account->wpAccountID());
        $accountMedia = $this->accountPostType->getMedia($this->account->wpAccountID());

        foreach ($feedsIds as $id) {
            $this->feedPostType->updateMedia($id, $accountMedia);
        }
    }
}