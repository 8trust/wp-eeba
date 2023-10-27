<?php

namespace Inavii\Instagram\RestApi\EndPoints\Feeds;

use Inavii\Instagram\Wp\ApiResponse;
use WP_REST_Response;

class FeedUpdateOrder
{
    private $api;

    public function __construct()
    {
        $this->api = new ApiResponse();
    }

    public function update(): WP_REST_Response
    {
        return $this->api->response([], false, 'Reordering or hiding media is only available in the PRO version');
    }
}