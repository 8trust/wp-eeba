<?php

namespace Inavii\Instagram\Cron\Accounts;

use Inavii\Instagram\Media\Media;
use Inavii\Instagram\PostTypes\Account\Account;
use Inavii\Instagram\PostTypes\Account\AccountPostType;
use Inavii\Instagram\Utils\CompareMedia;

class DeleteMedia
{
    private $account;
    private $accountPostType;
    private $remoteMedia;

    public function __construct(Account $account, $remoteMedia)
    {
        $this->account = $account;
        $this->accountPostType = new AccountPostType();
        $this->remoteMedia = $remoteMedia;
    }

    public function delete(): void
    {
        $actualMedia = $this->accountPostType->getMedia($this->account->wpAccountID());

        $newMedia = $this->remoteMedia;

        $diffMedia = new CompareMedia($newMedia, $actualMedia);

        $mediaToBeRemoved = $diffMedia->postsToBeDeleted();

        foreach ($mediaToBeRemoved as $item) {
            Media::delete($item['id']);

            if (!empty($post['children'])) {
                foreach ($post['children'] as $child) {
                    Media::delete($child['id']);
                }
            }
        }
    }
}