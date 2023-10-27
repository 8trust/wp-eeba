<?php

namespace Inavii\Instagram\RestApi\EndPoints\Feeds;

use Inavii\Instagram\Wp\ApiResponse;
use WP_REST_Response;

class FeedUpdateSettings
{
    private $api;

    public function __construct()
    {
        $this->api = new ApiResponse();
    }

    public function update(): WP_REST_Response
    {
        return $this->api->response([], false, 'Changing the layout is available only in the PRO version');
    }
}