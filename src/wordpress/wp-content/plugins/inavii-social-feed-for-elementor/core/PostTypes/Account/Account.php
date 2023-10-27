<?php

namespace Inavii\Instagram\PostTypes\Account;

class Account
{
    private $accountMeta;

    public function __construct($accountMeta)
    {
        $this->accountMeta = $accountMeta;
    }

    public function wpAccountID()
    {
        return $this->accountMeta['wpAccountID'];
    }

    public function igAccountID(): string
    {
        return $this->accountMeta['id'];
    }

    public function accountType(): string
    {
        return $this->accountMeta['accountType'] ?? '';
    }

    public function name(): string
    {
        return $this->accountMeta['name'] ?? '';
    }

    public function userName(): string
    {
        return $this->accountMeta['username'] ?? '';
    }

    public function instagramProfileLink(): string
    {
        return 'https://www.instagram.com/' . $this->accountMeta['username'];
    }

    public function accessToken(): string
    {
        return $this->accountMeta['accessToken'];
    }

    public function avatar()
    {
        return $this->accountMeta['avatar'] ?? '';
    }

    public function avatarOverwritten()
    {
        return $this->accountMeta['avatarOverwritten'] ?? '';
    }

    public function mediaCount()
    {
        return $this->accountMeta['mediaCount'];
    }

    public function tokenExpires()
    {
        return $this->accountMeta['tokenExpires'];
    }

    public function biography()
    {
        return $this->accountMeta['biography'] ?? '';
    }

    public function biographyOverwritten()
    {
        return $this->accountMeta['biographyOverwritten'] ?? '';
    }

    public function meta(): array
    {
        return $this->accountMeta;
    }
}