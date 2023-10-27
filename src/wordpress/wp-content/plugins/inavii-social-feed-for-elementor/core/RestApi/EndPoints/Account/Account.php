<?php

namespace Inavii\Instagram\RestApi\EndPoints\Account;

use Inavii\Instagram\Wp\ApiResponse;
use Inavii\Instagram\Wp\PostType;
use WP_REST_Request;
use WP_REST_Response;

class Account {

    private $account;
    private $api;

    public function __construct( PostType $account ) {
        $this->account = $account;
        $this->api     = new ApiResponse();
    }

    public function get(WP_REST_Request $request): WP_REST_Response
    {
        $wpAccountID = $request->get_param('wpAccountID');

        return $this->api->response( $this->account->getAccountMedia((int)$wpAccountID) );
    }
}