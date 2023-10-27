<?php

namespace Inavii\Instagram\PostTypes\Feed;

class Feed
{
    private  $id ;
    private  $mediaType ;
    private  $mediaUrl ;
    private  $permalink ;
    private  $username ;
    private  $name ;
    private  $children ;
    private  $videoUrl ;
    private  $date ;
    private  $commentsCount ;
    private  $likeCount ;
    private  $caption ;
    public function __construct(
        string $id,
        string $mediaType,
        array $mediaUrl,
        string $permalink,
        string $username,
        string $name,
        array $children,
        string $videoUrl,
        string $date,
        int $commentsCount,
        int $likeCount,
        string $caption
    )
    {
        $this->id = $id;
        $this->mediaType = $mediaType;
        $this->mediaUrl = $mediaUrl;
        $this->permalink = $permalink;
        $this->username = $username;
        $this->name = $name;
        $this->children = $children;
        $this->videoUrl = $videoUrl;
        $this->date = $date;
        $this->commentsCount = $commentsCount;
        $this->likeCount = $likeCount;
        $this->caption = $caption;
    }
    
    public function id() : int
    {
        return $this->id;
    }
    
    public function mediaType() : string
    {
        return $this->mediaType;
    }
    
    public function mediaUrl() : array
    {
        return $this->mediaUrl;
    }
    
    public function permalink() : string
    {
        return $this->permalink;
    }
    
    public function username() : string
    {
        return $this->username;
    }
    
    public function name() : string
    {
        return $this->name;
    }
    
    public function children() : array
    {
        return $this->children;
    }
    
    public function videoUrl() : string
    {
        return $this->videoUrl;
    }
    
    public function date() : string
    {
        return $this->date;
    }

}