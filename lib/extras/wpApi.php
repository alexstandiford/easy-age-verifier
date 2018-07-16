<?php
/**
 * $FILE_DESCRIPTION$
 * @author: Alex Standiford
 * @date  : 1/19/2017
 */

namespace eav\extras;


class wpApiQuery{

  public $posts;
  public $title;
  public $featuredImage;
  public $permalink;
  public $excerpt;
  public $isCaching;

  /**
   * wpApiQuery constructor. This is super simple right now, and I hope to add more arguments to it as I use it.
   *
   * @param $query
   *
   * Accepted fields (currently)
   * version: The version of the API you wish to use
   * posts_per_page: The number of posts you want to display per API call.
   * domain: The domain name of the WordPress website you wish to connect to
   */
  public function __construct($query){
    $defaults = array(
      'version'        => 'v2',
      'posts_per_page' => '10',
    );
    $query = array_replace($defaults, $query);
    $this->query = $query;
    $this->url = "http://www.".$query['domain'].'/';
    $this->apiUrl = 'http://www.'.$query['domain'].'/wp-json/wp/'.$query['version'].'/';
  }

  public function get(){
    if(!get_option('fyt_posts_cache')) $this->cachePosts();

    return get_option('fyt_posts_cache');
  }

  public function cachePosts(){
    $file = file_get_contents($this->apiUrl.'posts?per_page='.$this->query['posts_per_page']);
    $this->posts = json_decode($file);
    $cache = array();
    $this->isCaching = true;
    while($this->havePosts()){
      $this->thePost();
      $current_post = new \stdClass();
      $current_post->title = $this->title;
      $current_post->permalink = $this->permalink;
      $current_post->excerpt = $this->excerpt;
      $current_post->featuredImage = $this->featuredImage;
      $cache[] = $current_post;
    }
    $this->isCaching = false;
    update_option('fyt_posts_cache', $cache);
  }

  public static function saveToCache($query){
    $self = new self($query);
    $self->cachePosts();
  }

  /**
   * Gets the posts from the specified URL
   *
   * @return array|mixed|object
   */
  public function getPosts(){
    if($this->posts){
      return $this->posts;
    }
    $posts = $this->get();
    $this->posts = $posts;

    return $posts;
  }

  /*
   * Just like WordPress' the_post() function. This grabs the next post's data, and shifts the last post off of the query array
   *
   * @return void
   */
  public function thePost(){
    if($this->isCaching){
      $current_post = $this->posts[0];
      $this->title = $current_post->title->rendered;
      $this->permalink = $current_post->guid->rendered;
      $this->excerpt = $current_post->excerpt->rendered;
      $this->featuredImage = $this->getFeaturedImage($current_post->featured_media);
    }
    else{
      $current_post = $this->posts[0];
      $this->title = $current_post->title;
      $this->permalink = $current_post->permalink;
      $this->excerpt = $current_post->excerpt;
      $this->featuredImage = $current_post->featuredImage;
    }
    array_shift($this->posts);
  }

  /*
   * Just like WordPress' have_posts() function. This checks to see if there are any more posts to get.
   *
   * @return bool
   */
  public function havePosts(){
    if($this->posts === null){
      $this->getPosts();
    }
    $post_count = count($this->posts);
    if($post_count > 0){
      return true;
    }
    else{
      return false;
    }
  }

  /**
   * Grabs the featured image of the current post. Can work in the loop, or outside of the loop if you specify a post ID
   *
   * @param null $id
   *
   * @return array|bool
   */
  private function getFeaturedImage($id = null){
    $images = array();
    if($id == null){
      return false;
    }
    else{
      $featured_image_id = $id;
    }
    $image_url = $this->apiUrl.'media/'.$featured_image_id;
    $image_json = file_get_contents($image_url);
    $image_json = json_decode($image_json);
    $sizes = $image_json->media_details->sizes;
    foreach($sizes as $size => $object){
      $images[$size] = $object->source_url;
    }

    return $images;
  }
}