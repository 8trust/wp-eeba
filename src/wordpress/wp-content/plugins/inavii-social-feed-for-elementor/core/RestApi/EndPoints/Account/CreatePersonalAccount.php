<?php

namespace Inavii\Instagram\RestApi\EndPoints\Account;

use Inavii\Instagram\Services\Instagram\Account\PersonalAccountService;
use Inavii\Instagram\Wp\ApiResponse;
use WP_REST_Request;
use WP_REST_Response;

class CreatePersonalAccount
{
    private $api;

    public function __construct()
    {
        $this->api = new ApiResponse();
    }

    public function create(WP_REST_Request $request): WP_REST_Response
    {
        $params = $request->get_params();

        $account = (new PersonalAccountService($params['accessToken'], $params['tokenExpires']))->get();

        return $this->api->response(
            (new CreateAccount('personal'))->create($account)
        );
    }
}