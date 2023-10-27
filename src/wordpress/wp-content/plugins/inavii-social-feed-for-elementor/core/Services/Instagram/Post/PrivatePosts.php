<?php

namespace Inavii\Instagram\Services\Instagram\Post;

use Inavii\Instagram\Services\Instagram\Integration;

class PrivatePosts implements Posts
{
    use RequestsPosts;

    private $integration;

    public function __construct()
    {
        $this->integration = new Integration();
    }

    private function requestMedia(string $accessToken, int $limit): array
    {
        return $this->getMedia(
            $this->integration->buildUrl('https://graph.instagram.com/v16.0/me/media', [
                'access_token' => $accessToken,
                'limit' => $limit,
                'fields' => (new FieldsBuilder(false))->getAllFieldsAsString(),
            ])
        );
    }
}