<?php

namespace Inavii\Instagram\Services\Instagram;

use Inavii\Instagram\Utils\Json;

class Integration
{
    private $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * @throws MessageNotProvidedException
     */
    public function get(string $url, $params): array
    {
        $response = $this->request->get($url, $params);

        $code = $response->responseCode();

        if (200 === $code) {
            return Json::decode($response->read());
        }

        $body = Json::decode($response->read());

        if (isset($body['error']['message'])) {
            throw new \RuntimeException($body['error']['message']);
        }
        throw new MessageNotProvidedException("Message not provided");
    }

    public function getMedia($url)
    {
        return \wp_remote_get($url, ['timeout' => 180]);
    }

    public function buildUrl(string $url, $params): string
    {
        return $this->request->buildUrl($url, $params);
    }
}
