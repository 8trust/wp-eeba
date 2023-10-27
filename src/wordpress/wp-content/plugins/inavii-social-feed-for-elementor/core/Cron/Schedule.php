<?php

namespace Inavii\Instagram\Cron;

use Inavii\Instagram\Cron\Feeds\UpdateFeedAddNewMedia;
use Inavii\Instagram\PostTypes\Account\Account;
use Inavii\Instagram\PostTypes\Account\AccountPostType;
use Inavii\Instagram\Cron\Accounts\AddNewMedia;
use Inavii\Instagram\Cron\Accounts\DeleteMedia;
use Inavii\Instagram\Utils\TransformRemotenIstagramData;
use Inavii\Instagram\Wp\AdminNoticeLog;

class Schedule
{
    private $accountPostType;

    use RequestMedia;

    public function __construct()
    {
        add_action('inavii_social_feed_update_media', [$this, 'updateMedia']);
        add_action('inavii_social_feed_refresh_token', [$this, 'refreshAccessToken']);
        $this->accountPostType = new AccountPostType();
    }

    public function updateMedia(): void
    {
        foreach ($this->accountPostType->posts() as $account) {
            $accountObj = new Account($account);

            try {
                $remoteMedia = TransformRemotenIstagramData::transform($this->remoteMedia($accountObj));

                (new DeleteMedia($accountObj, $remoteMedia))->delete();

                (new AddNewMedia($accountObj, $remoteMedia))->addMedia();

                (new UpdateFeedAddNewMedia($accountObj))->update();

                AdminNoticeLog::removeAccountOption('notice_token_error', $accountObj->wpAccountID());
                $this->accountPostType->instagramFeedsLastUpdate($accountObj->wpAccountID());

            } catch (\RuntimeException $e) {
                AdminNoticeLog::removeAccountOption('notice_token_error', $accountObj->wpAccountID());
                AdminNoticeLog::addOption('notice_token_error', $accountObj->wpAccountID(),  $accountObj->name() ??  $accountObj->userName() , $e->getMessage());
            }
        }
    }

    public function updateSingleAccount($accountID)
    {
        $account = $this->accountPostType->get($accountID);

        try {
            $remoteMedia = TransformRemotenIstagramData::transform($this->remoteMedia($account));

            (new DeleteMedia($account, $remoteMedia))->delete();
            (new AddNewMedia($account, $remoteMedia))->addMedia();
            (new UpdateFeedAddNewMedia($account))->update();

            AdminNoticeLog::removeAccountOption('notice_token_error', $accountID);
            $this->accountPostType->instagramFeedsLastUpdate($accountID);

        } catch (\RuntimeException $e) {
            AdminNoticeLog::addOption('notice_token_error', $accountID, $account->name() ??  $account->userName(), $e->getMessage());
        }

        return $account->wpAccountID();
    }

    public function refreshAccessToken(): void
    {
        foreach ($this->accountPostType->posts() as $account) {
            (new RefreshAccessToken(new Account($account)))->refresh();
        }
    }
}

