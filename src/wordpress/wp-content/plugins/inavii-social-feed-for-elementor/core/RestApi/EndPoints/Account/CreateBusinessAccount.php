<?php

namespace Inavii\Instagram\RestApi\EndPoints\Account;

use Inavii\Instagram\Services\Instagram\Account\BusinessAccountService;
use Inavii\Instagram\Wp\ApiResponse;
use WP_REST_Request;
use WP_REST_Response;

class CreateBusinessAccount
{
    private $api;

    public function __construct()
    {
        $this->api = new ApiResponse();
    }

    public function create(WP_REST_Request $request): WP_REST_Response
    {
        $params = $request->get_param('data');

        $account = (new BusinessAccountService($params['accessToken'], $params['tokenExpires'] ?? ''))->get($params['userID']);

        return $this->api->response(
            (new CreateAccount('business'))->create($account)
        );
    }
}