<?php

namespace Inavii\Instagram\PostTypes\Feed;

use Inavii\Instagram\PostTypes\Account\AccountPostType;
use Inavii\Instagram\Wp\PostType;
use Inavii\Instagram\Wp\Query;

class FeedPostType extends PostType
{

    public const META_KEY_FEEDS = 'inavii_social_feed_feeds';
    public const META_KEY_FEEDS_SETTINGS = 'inavii_social_feed_settings';
    public const META_KEY_ACCOUNT_RELATED = 'inavii_social_feed_account_related';

    public function slug(): string
    {
        return 'inavii_feed';
    }

    public function insert(string $title, array $data, ...$params): int
    {
        return (new Query($this->slug()))->withPostTitle($title)
                                         ->withMetaInput(self::META_KEY_FEEDS, $data)
                                         ->withMetaKey($params[0])
                                         ->save();
    }

    public function addRelatedAccount($postID, $data): void
    {
        $this->updateMeta($postID, self::META_KEY_ACCOUNT_RELATED, $data);
    }

    public function addOrUpdateSettings($postID, $data): void
    {
        $this->updateMeta($postID, self::META_KEY_FEEDS_SETTINGS, $data);
    }

    public function getSettings($postID): array
    {
        return (array) $this->getMeta($postID, self::META_KEY_FEEDS_SETTINGS);
    }

    public function get(int $postID, bool $object = true, int $numberOfPosts = null): array
    {
        $meta = $this->getMeta($postID, self::META_KEY_FEEDS);

        if ( ! $meta) {
            return [];
        }

        if ($numberOfPosts) {
            $meta = array_slice($meta, 0, $numberOfPosts);
        }

        if ($object === false) {
            return array_values($meta);
        }

        return \iterator_to_array($this->preparePosts($meta));
    }

    private function preparePosts($posts): \Generator
    {
        foreach ($posts as $post) {
            yield new Feed(
                $post['id'],
                $post['mediaType'],
                $post['mediaUrl'],
                $post['permalink'],
                $post['username'] ?? '',
                $post['name'] ?? '',
                $post['children'] ?? [],
                $post['videoUrl'] ?? '',
                $post['date'] ?? '',
                $post['commentsCount'] ?? 0,
                $post['likeCount'] ?? 0,
                $post['caption'] ?? ''
            );
        }
    }

    public function posts(): array
    {
        return array_map(function ($post) {
            return $this->serializeData($post);
        }, (new Query($this->slug()))->numberOfPosts()->order('ASC')->posts());
    }

    public function post($postID): array
    {
        return $this->serializeData((new Query($this->slug()))->post($postID));
    }

    private function serializeData($post): array
    {
        $account = (new AccountPostType())->get((int) $this->getMeta($post->ID, self::META_KEY_ACCOUNT_RELATED));
        $media   = $this->get($post->ID, false);

        return [
            'id'       => $post->ID,
            'title'    => $post->post_title,
            'account'  => [
                'name'       => $account->name(),
                'userName'   => $account->userName(),
                'mediaCount' => count($media),
                'avatar'     => $account->avatar(),
                'avatarOverwritten' => $account->avatarOverwritten(),
                'type'       => $account->accountType(),
            ],
            'settings' => $this->getSettings($post->ID),
            'media'    => $media,
        ];
    }

    public function getAccounts(): array
    {
        $results = [];
        $posts   = $this->posts();

        if ( ! $posts) {
            return [];
        }

        foreach ($posts as $post) {
            $results[$post['id'] .':'. $post['settings']['layout'] ] = $post['title'] . ' ' . '('.$post['settings']['layout'] .')';
        }

        return $results;
    }

    public function getRelatedFeedsIds($accountID): array
    {
        return (new Query($this->slug()))->withMetaQuery(self::META_KEY_ACCOUNT_RELATED, $accountID)
                                         ->withFields('ids')
                                         ->numberOfPosts()
                                         ->posts();
    }

    public function updateMedia(int $postID, array $data): void
    {
        $this->updateMeta($postID, self::META_KEY_FEEDS, $data);
    }

    protected function args(): array
    {
        return array_merge(
            parent::args(),
            [
                'labels' => [
                    'menu_name' => __('Inavii Feed', 'text_domain'),
                ],
            ]
        );
    }
}


