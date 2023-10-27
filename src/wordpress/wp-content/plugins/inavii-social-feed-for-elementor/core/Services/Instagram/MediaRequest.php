<?php

namespace Inavii\Instagram\Services\Instagram;

use Inavii\Instagram\PostTypes\Account\Account;
use Inavii\Instagram\PostTypes\Account\AccountPostType;
use Inavii\Instagram\Services\Instagram\Post\BusinessPosts;
use Inavii\Instagram\Services\Instagram\Post\PrivatePosts;
use Inavii\Instagram\Utils\TransformRemotenIstagramData;

class MediaRequest
{
    private $account;
    private $accountType;

    public function __construct(Account $account, $accountType)
    {
        $this->account = $account;
        $this->accountType = $accountType;
    }

    public function request(): array
    {
        if ($this->accountType === 'business' || $this->accountType === AccountPostType::BUSINESS) {
            $instagramFetch = new BusinessPosts($this->account->igAccountID());
        } else {
            $instagramFetch = new PrivatePosts();
        }

        return TransformRemotenIstagramData::transform($instagramFetch->posts($this->account->accessToken()));
    }
}