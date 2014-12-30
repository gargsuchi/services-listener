<?php
namespace Drupal\twitter;

class TwitterUser {

  public $id;

  public $screen_name;

  public $name;

  public $location;

  public $description;

  public $followers_count;

  public $friends_count;

  public $statuses_count;

  public $favourites_count;

  public $url;

  public $protected;

  public $profile_image_url;

  public $profile_background_color;

  public $profile_text_color;

  public $profile_link_color;

  public $profile_sidebar_fill_color;

  public $profile_sidebar_border_color;

  public $profile_background_image_url;

  public $profile_background_tile;

  public $verified;

  public $created_at;

  public $created_time;

  public $utc_offset;

  public $status;

  protected $oauth_token;

  protected $oauth_token_secret;

  public function __construct($values = array()) {
    $this->id = $values['id'];
    $this->screen_name = $values['screen_name'];
    $this->name = $values['name'];
    $this->location = $values['location'];
    $this->description = $values['description'];
    $this->url = $values['url'];
    $this->followers_count = $values['followers_count'];
    $this->friends_count = $values['friends_count'];
    $this->statuses_count = $values['statuses_count'];
    $this->favourites_count = $values['favourites_count'];
    $this->protected = $values['protected'];
    $this->profile_image_url = $values['profile_image_url'];
    $this->profile_background_color = $values['profile_background_color'];
    $this->profile_text_color = $values['profile_text_color'];
    $this->profile_link_color = $values['profile_link_color'];
    $this->profile_sidebar_fill_color = $values['profile_sidebar_fill_color'];
    $this->profile_sidebar_border_color = $values['profile_sidebar_border_color'];
    $this->profile_background_image_url = $values['profile_background_image_url'];
    $this->profile_background_tile = $values['profile_background_tile'];
    $this->verified = $values['verified'];
    $this->created_at = $values['created_at'];
    if (!empty($values['uid'])) {
      $this->uid = $values['uid'];
    }
    if (!empty($values['created_at']) && $created_time = strtotime($values['created_at'])) {
      $this->created_time = $created_time;
    }
    $this->utc_offset = $values['utc_offset']?$values['utc_offset']:0;

    if (isset($values['status'])) {
      $this->status = new TwitterStatus($values['status']);
    }
  }

  /**
   * Returns an array with the authentication tokens.
   *
   * @return
   *   array with the oauth token key and secret.
   */
  public function get_auth() {
    return array('oauth_token' => $this->oauth_token, 'oauth_token_secret' => $this->oauth_token_secret);
  }

  /**
   * Sets the authentication tokens to a user.
   *
   * @param array $values
   *   Array with 'oauth_token' and 'oauth_token_secret' keys.
   */
  public function set_auth($values) {
    $this->oauth_token = isset($values['oauth_token'])?$values['oauth_token']:NULL;
    $this->oauth_token_secret = isset($values['oauth_token_secret'])?$values['oauth_token_secret']:NULL;
  }

  /**
   * Checks whether the account is authenticated or not.
   *
   * @return
   *   boolean TRUE when the account is authenticated.
   */
  public function is_auth() {
    return !empty($this->oauth_token) && !empty($this->oauth_token_secret);
  }
}
