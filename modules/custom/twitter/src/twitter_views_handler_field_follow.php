<?php
namespace Drupal\twitter;

/**
 * Adds Twitter Follow link.
 *
 * @see https://dev.twitter.com/docs/intents#follow-intent
 */
class twitter_views_handler_field_follow extends views_handler_field {
  function query() {}

  function render($values) {
    _drupal_add_js('//platform.twitter.com/widgets.js', 'external');
    return '<span><a href="https://twitter.com/intent/user?screen_name=' . $values->twitter_screen_name . '">Follow</a></span>';
  }
}
