<?php

namespace Inavii\Instagram\Services\Instagram\Account;

use Inavii\Instagram\Services\Instagram\Integration;

class PersonalAccountService implements Account
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

    public function get(): InstagramAccount
    {
        $response = $this->integration->get('https://graph.instagram.com/v16.0/me', [
            'fields' => 'id,username,media_count,account_type',
            'access_token' => $this->accessToken,
        ]);

        return new InstagramAccount(array_merge($response, [
            'accessToken' => $this->accessToken,
            'tokenExpires' => $this->tokenExpires,
            'account_type' => 'personal'
        ]));
    }
}
