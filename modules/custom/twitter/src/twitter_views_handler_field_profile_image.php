<?php
namespace Drupal\twitter;

/**
 * Field handler to provide simple renderer that turns a URL into a clickable link.
 */
class twitter_views_handler_field_profile_image extends views_handler_field {
  function render($values) {
    $value = $values->{$this->field_alias};
    return _theme('image', array('path' => $value));
  }
}
