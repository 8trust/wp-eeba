<?php

namespace Inavii\Instagram\Services\Instagram\Post;

use Inavii\Instagram\Utils\Json;

trait RequestsPosts
{
    public function posts(string $accessToken, int $limit = 50): array
    {
        return $this->requestMedia($accessToken, $limit);
    }

    private function getMedia($url): array
    {
        $media = [];
        $count = 0;

        while ($url && $count < 100) {

            $response = $this->integration->getMedia($url);

            if ((wp_remote_retrieve_response_code($response) === 400)) {
                throw new \RuntimeException(json_decode(wp_remote_retrieve_body($response), false)->error->message, 400);
            }

            $data = json_decode($response['body'], true);

            $pageMediaItems = $data["data"];

            $media[] = $pageMediaItems;
            $count += count($pageMediaItems);

            $url = $data["paging"]["next"] ?? false;
        }

        $result = array_merge([], ...$media);

        return array_slice($result, 0, 100);
    }
}
