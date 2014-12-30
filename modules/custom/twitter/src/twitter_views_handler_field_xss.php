<?php
namespace Drupal\twitter;

/**
 * Process Twitter-style @usernames and URLs before filtering XSS.
 */
class twitter_views_handler_field_xss extends views_handler_field {
  function option_definition() {
    $options = parent::option_definition();
    $options['link_urls'] = array('default' => TRUE);
    $options['link_usernames'] = array('default' => TRUE);
    $options['link_hashtags'] = array('default' => FALSE);

    $options['hashtags_url'] = array(
      'default' => \Drupal::config('twitter.settings')
          ->get('twitter_search') . '/search?q=%23'
    );
    $options['link_attributes'] = array('default' => TRUE);
    return $options;
  }

  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['link_urls'] = array(
      '#title' => t('Link urls to their destinations'),
      '#type' => 'checkbox',
      '#default_value' => !empty($this->options['link_urls']),
    );
    $form['link_usernames'] = array(
      '#title' => t('Link Twitter @usernames to their Twitter.com urls'),
      '#type' => 'checkbox',
      '#default_value' => !empty($this->options['link_usernames']),
    );
    $form['link_hashtags'] = array(
      '#title' => t('Link Twitter #hashtags to another url'),
      '#type' => 'checkbox',
      '#default_value' => !empty($this->options['link_hashtags']),
    );
    $form['hashtags_url'] = array(
      '#type' => 'textfield',
      '#default_value' => $this->options['hashtags_url'],
      '#process' => array('ctools_dependent_process'),
      '#dependency' => array('edit-options-link-hashtags' => array(TRUE)),
    );
    $form['link_attributes'] = array(
      '#title' => t('Open links in new windows/tabs and add rel="nofollow" attributes.'),
      '#type' => 'checkbox',
      '#default_value' => !empty($this->options['link_attributes']),
    );
  }

  /**
   * Processes the message through the selected options.
   */
  function render($values) {
    $value = $values->{$this->field_alias};
    if (!empty($this->options['link_urls'])) {
      $filter = new stdClass;
      $filter->settings = array(
        'filter_url_length' => 496,
      );
      $value = _filter_url($value, $filter);
    }
    // Link usernames with their profiles.
    if (!empty($this->options['link_usernames'])) {

      $value = _twitter_filter_text($value, '@', \Drupal::config('twitter.settings')->get('twitter_host') . '/');
    }
    // Link hashtags.
    if (!empty($this->options['link_hashtags']) && valid_url($this->options['hashtags_url'])) {
      $value = _twitter_filter_text($value, '#', _url($this->options['hashtags_url']));

    }
    // Add extra attributes to links.
    if (!empty($this->options['link_attributes'])) {
      $value = _twitter_filter_link($value, NULL);
    }
    // Avoid XSS within the message.
    return filter_xss($value);
  }
}
