<?php
namespace Drupal\twitter;

/**
 * Renders a tweet as it is presented at Twitter.com.
 *
 * @see https://dev.twitter.com/terms/display-requirements
 */
class twitter_views_handler_field_formatted_tweet extends views_handler_field {
  function query() {}

  function render($values) {

    _drupal_add_js('//platform.twitter.com/widgets.js', 'external');
    _drupal_add_css(drupal_get_path('module', 'twitter') . '/twitter.css');

    module_load_include('inc', 'twitter');

    // Load tweet and author.
    $status = twitter_status_load($values->twitter_id);
    $author = twitter_account_load($status->screen_name);

    // Format the timestamp.
    $time_diff = REQUEST_TIME - $values->twitter_created_time;

    // Format the message.
    $status->time_ago = t('%time ago', array('%time' => \Drupal::service("date.formatter")->formatInterval($time_diff, 2)));
    $filter = new stdClass;
      $filter->settings = array(
        'filter_url_length' => 496,
      );
    $status->text = _filter_url($status->text, $filter);

    // Render the tweet.
    return _theme('twitter_status', array(
      'status' => $status,
      'author' => $author,
    ));
  }
}
