<?php

namespace Inavii\Instagram\Services\Instagram\Account;

use Inavii\Instagram\Services\Instagram\Integration;

class BusinessAccountService
{
    private $accessToken;
    private $integration;
    private $tokenExpires;

    public function __construct(string $accessToken, string $tokenExpires)
    {
        $this->accessToken = $accessToken;
        $this->integration = new Integration();
        $this->tokenExpires = $tokenExpires;
    }

    public function get($id): InstagramAccount
    {
        $response = $this->integration->get("https://graph.facebook.com/v16.0/$id", [
            'fields' => 'id,name,username,profile_picture_url,media_count,biography',
            'access_token' => $this->accessToken,
        ]);

        return new InstagramAccount(array_merge($response, [
            'accessToken' => $this->accessToken,
            'tokenExpires' => $this->tokenExpires,
            'account_type' => 'business'
        ]));
    }
}
