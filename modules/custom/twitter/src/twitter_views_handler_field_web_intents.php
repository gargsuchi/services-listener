<?php
namespace Drupal\twitter;

/**
 * Adds Twitter Intents links.
 *
 * @see https://dev.twitter.com/docs/intents
 */
class twitter_views_handler_field_web_intents extends views_handler_field {
  /**
   * Add twitter_id field, which is needed during rendering.
   */
  function construct() {
    parent::construct();
    $this->additional_fields['twitter_id'] = 'twitter_id';
  }

  function query() {
    $this->ensure_my_table();
    $this->add_additional_fields();
  }

  function render($values) {
    _drupal_add_js('//platform.twitter.com/widgets.js', 'external');

    return '<span><a href="https://twitter.com/intent/tweet?in_reply_to=' . $values->twitter_id . '">Reply</a></span> ' .
    '<span><a href="https://twitter.com/intent/retweet?tweet_id=' . $values->twitter_id . '">Retweet</a></span> ' .
    '<span><a href="https://twitter.com/intent/favorite?tweet_id=' . $values->twitter_id . '">Favorite</a></span>';
  }
}
