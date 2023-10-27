<?php

namespace Inavii\Instagram\RestApi\EndPoints\Account;

use Inavii\Instagram\Cron\Schedule;
use Inavii\Instagram\Wp\ApiResponse;
use Inavii\Instagram\Wp\PostType;
use WP_REST_Request;
use WP_REST_Response;

class Cron
{

    private $api;

    public function __construct()
    {
        $this->api = new ApiResponse();
    }

    public function run(WP_REST_Request $request): WP_REST_Response
    {
        $apiData = $request->get_params();

        $cron = new Schedule();
        $cron->updateSingleAccount($apiData['accountId']);

        return $this->api->response([]);
    }
}