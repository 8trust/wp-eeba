<?php

namespace Inavii\Instagram\RestApi\EndPoints\Account;

use Inavii\Instagram\PostTypes\Account\AccountPostType;
use Inavii\Instagram\PostTypes\Feed\FeedPostType;
use Inavii\Instagram\Wp\ApiResponse;
use WP_REST_Request;
use WP_REST_Response;

class AccountDelete
{
    private $api;
    private $feed;
    private $account;

    public function __construct()
    {
        $this->api     = new ApiResponse();
        $this->feed    = new FeedPostType();
        $this->account = new AccountPostType();
    }
    public function delete(WP_REST_Request $request): WP_REST_Response
    {
        $accountID = $request->get_param('id');

        $feedsIds        = $this->feed->getRelatedFeedsIds($accountID);
        $deletedFeedsIds = [];

        if ($feedsIds) {
            foreach ($feedsIds as $id) {
                $deletedFeedsIds[] = $this->feed->delete($id);
            }
        }

        $this->account->deleteMedia($accountID);
        $this->account->delete($accountID);

        return $this->api->response(array_column($deletedFeedsIds, 'ID'));
    }
}
