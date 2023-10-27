<?php

namespace Inavii\Instagram\Wp;

class Query
{
    private $query = [];

    public function __construct(string $postTypeSlug)
    {
        $this->query['post_type']   = $postTypeSlug;
        $this->query['post_status'] = 'publish';
    }

    public function withPostTitle(string $postTitle): self
    {
        $this->query['post_title'] = $postTitle;

        return $this;
    }

    public function withMetaInput(string $key, $value): self
    {
        $this->query['meta_input'][$key] = $value;

        return $this;
    }

    public function withMetaKey(string $key): self
    {
        $this->query['meta_key'] = $key;

        return $this;
    }

    public function withMetaQuery(string $key, $value, string $compare = '='): self
    {
        $this->query['meta_query'][] = [
            'key'     => $key,
            'value'   => $value,
            'compare' => $compare,
        ];

        return $this;
    }

    public function numberOfPosts(int $number = -1): self
    {
        $this->query['posts_per_page'] = $number;

        return $this;
    }

    public function order($order): self
    {
        $this->query['order'] = $order;

        return $this;
    }

    public function save()
    {
        $isPostsExsist = get_page_by_title($this->query['post_title'], 'OBJECT', $this->query['post_type']);

        if ($isPostsExsist && $isPostsExsist->ID) {
            return wp_update_post(array_merge($this->query, ['ID' => $isPostsExsist->ID]));
        }

        return wp_insert_post($this->query);
    }

    public function withFields($param): self
    {
        $this->query['fields'] = $param;

        return $this;
    }

    public function posts(): array
    {
        return get_posts($this->query);
    }

    public function post($postID)
    {
        return get_post($postID);
    }
}
