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

  public function get($url){
    $file = file_get_contents($url);
    $json = json_decode($file);

    return $json;
  }

  public function getPosts(){
    if($this->posts){
      return $this->posts;
    }
    $posts = $this->get($this->apiUrl.'posts?per_page='.$this->query['posts_per_page']);
    $this->posts = $posts;

    return $posts;
  }

  public function thePost(){
    $current_post = $this->posts[0];
    $this->title = $current_post->title->rendered;
    $this->permalink = $current_post->guid->rendered;
    $this->excerpt = $current_post->excerpt->rendered;
    $this->featuredImage = $this->getFeaturedImage($current_post->featured_media);
    array_shift($this->posts);
  }

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

  private function getFeaturedImage($id = null){
    $images = array();
    if($id == null){
      return false;
    }
    else{
      $featured_image_id = $id;
    }
    $image_url = $this->apiUrl.'media/'.$featured_image_id;
    $image_json = $this->get($image_url);
    $sizes = $image_json->media_details->sizes;
    foreach($sizes as $size => $object){
      $images[$size] = $object->source_url;
    }

    return $images;
  }

}