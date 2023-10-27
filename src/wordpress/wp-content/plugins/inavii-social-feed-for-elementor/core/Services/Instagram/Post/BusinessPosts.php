<?php


namespace Inavii\Instagram\Services\Instagram\Post;

use Inavii\Instagram\Services\Instagram\Integration;

class BusinessPosts implements Posts
{

    use RequestsPosts;

    private $integration;
    private $userId;

    public function __construct(int $userId)
    {
        $this->integration = new Integration();
        $this->userId      = $userId;
    }

    private function requestMedia(string $accessToken, int $limit): array
    {
        return $this->getMedia(
            $this->integration->buildUrl("https://graph.facebook.com/v16.0/$this->userId/media", [
                'access_token' => $accessToken,
                'limit'        => $limit,
                'fields'       => (new FieldsBuilder( true))->getAllFieldsAsString(),
            ])
        );
    }
}
