<?php

namespace Inavii\Instagram\RestApi;

use  Inavii\Instagram\PostTypes\Account\AccountPostType ;
use  Inavii\Instagram\RestApi\EndPoints\Account\AccessTokenGenerator ;
use  Inavii\Instagram\RestApi\EndPoints\Account\Account ;
use  Inavii\Instagram\RestApi\EndPoints\Account\AccountDelete ;
use  Inavii\Instagram\RestApi\EndPoints\Account\Accounts ;
use  Inavii\Instagram\RestApi\EndPoints\Account\CreateBusinessAccount ;
use  Inavii\Instagram\RestApi\EndPoints\Account\CreatePersonalAccount ;
use  Inavii\Instagram\RestApi\EndPoints\Account\Cron ;
use  Inavii\Instagram\RestApi\EndPoints\Account\UpdateAccount ;
use  Inavii\Instagram\RestApi\EndPoints\Account\UpdateAccountBio ;
use  Inavii\Instagram\RestApi\EndPoints\Feeds\Feed ;
use  Inavii\Instagram\RestApi\EndPoints\Feeds\FeedCreate ;
use  Inavii\Instagram\RestApi\EndPoints\Feeds\FeedDelete ;
use  Inavii\Instagram\RestApi\EndPoints\Feeds\Feeds ;
use  Inavii\Instagram\RestApi\EndPoints\Feeds\FeedUpdateOrder ;
use  Inavii\Instagram\RestApi\EndPoints\Feeds\FeedUpdateSettings ;
use  Inavii\Instagram\RestApi\EndPoints\App\Settings ;
use  Inavii\Instagram\Utils\VersionChecker ;
class RegisterRestApi
{
    private static  $nameSpace = 'inavii/v1' ;
    private static function config() : array
    {
        return [
            [
            'route'    => 'app/settings',
            'methods'  => 'GET',
            'callback' => [ new Settings(), 'settings' ],
        ],
            [
            'route'    => 'instagram/personal/account/create',
            'methods'  => 'POST',
            'callback' => [ new CreatePersonalAccount(), 'create' ],
        ],
            [
            'route'    => 'instagram/business/account/create',
            'methods'  => 'POST',
            'callback' => [ new CreateBusinessAccount(), 'create' ],
        ],
            [
            'route'    => 'instagram/accessTokenGenerator',
            'methods'  => 'POST',
            'callback' => [ new AccessTokenGenerator(), 'get' ],
        ],
            [
            'route'    => 'account',
            'methods'  => 'GET',
            'callback' => [ new Accounts( new AccountPostType() ), 'get' ],
        ],
            [
            'route'    => 'get/account',
            'methods'  => 'GET',
            'callback' => [ new Account( new AccountPostType() ), 'get' ],
        ],
            [
            'route'    => 'account/update',
            'methods'  => 'POST',
            'callback' => [ new UpdateAccount( new AccountPostType() ), 'update' ],
        ],
            [
            'route'    => 'account/delete/(?P<id>\\d+)',
            'methods'  => 'DELETE',
            'callback' => [ new AccountDelete(), 'delete' ],
        ],
            [
            'route'    => 'feed/(?P<id>\\d+)',
            'methods'  => 'GET',
            'callback' => [ new Feed(), 'get' ],
        ],
            [
            'route'    => 'feeds',
            'methods'  => 'GET',
            'callback' => [ new Feeds(), 'get' ],
        ],
            [
            'route'    => 'feeds/create',
            'methods'  => 'POST',
            'callback' => [ new FeedCreate(), 'create' ],
        ],
            [
            'route'    => 'feeds/delete/(?P<id>\\d+)',
            'methods'  => 'DELETE',
            'callback' => [ new FeedDelete(), 'delete' ],
        ],
            [
            'route'    => 'feeds/update/order',
            'methods'  => 'POST',
            'callback' => [ new FeedUpdateOrder(), 'update' ],
        ],
            [
            'route'    => 'feeds/update/settings',
            'methods'  => 'PUT',
            'callback' => [ new FeedUpdateSettings(), 'update' ],
        ],
            [
            'route'    => 'account/cron',
            'methods'  => 'PUT',
            'callback' => [ new Cron(), 'run' ],
        ]
        ];
    }
    
    public static function registerRoute() : void
    {
        $mergedConfig = self::config();
        foreach ( $mergedConfig as $config ) {
            register_rest_route( self::$nameSpace, $config['route'], [
                'methods'             => $config['methods'],
                'callback'            => $config['callback'],
                'permission_callback' => function () {
                return \current_user_can( 'manage_options' );
            },
            ] );
        }
    }

}