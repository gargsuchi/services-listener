<?php
namespace Drupal\twitter;

/**
 * Class for containing an individual twitter status.
 */
class TwitterStatus {
  /**
   * @var created_at
   */
  public $created_at;

  public $id;

  public $text;

  public $source;

  public $truncated;

  public $favorited;

  public $in_reply_to_status_id;

  public $in_reply_to_user_id;

  public $in_reply_to_screen_name;

  public $user;

  /**
   * Constructor for TwitterStatus
   */
  public function __construct($values = array()) {
    $this->created_at = $values['created_at'];
    $this->id = $values['id'];
    $this->text = $values['text'];
    $this->source = $values['source'];
    $this->truncated = $values['truncated'];
    $this->favorited = $values['favorited'];
    $this->in_reply_to_status_id = $values['in_reply_to_status_id'];
    $this->in_reply_to_user_id = $values['in_reply_to_user_id'];
    $this->in_reply_to_screen_name = $values['in_reply_to_screen_name'];
    if (isset($values['user'])) {
      $this->user = new TwitterUser($values['user']);
    }
  }
}
