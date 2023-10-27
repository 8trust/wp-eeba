<?php

namespace Inavii\Instagram\RestApi\EndPoints\App;

use Inavii\Instagram\Media\Media;
use Inavii\Instagram\Utils\VersionChecker;
use Inavii\Instagram\Wp\AdminNoticeLog;
use Inavii\Instagram\Wp\ApiResponse;
use WP_REST_Response;

class Settings
{

    private $api;

    public function __construct()
    {
        $this->api = new ApiResponse();
    }

    public function settings(): WP_REST_Response
    {
        return $this->api->response(
            [
                'isPro' => VersionChecker::version()->can_use_premium_code(),
                'gdLibraryAvailability' => Media::checkGDLibraryAvailability(),
                'timeZone' =>  wp_timezone_string(),
                'errorLog' => AdminNoticeLog::getErrors('notice_token_error')
            ]
        );
    }
}
