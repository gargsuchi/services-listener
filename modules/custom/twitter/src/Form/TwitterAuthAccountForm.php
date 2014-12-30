<?php

/**
 * @file
 * Contains \Drupal\twitter_search\Form\TwitterSearchAddForm.
 */

namespace Drupal\twitter\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\twitter\Twitter;

class TwitterAuthAccountForm extends FormBase {
  protected $user;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'twitter_auth_account_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Go to Twitter to add an authenticated account'),
      '#prefix' => t('Authenticated accounts can post, sign in and pull mentions. ' .
        'At least one authenticated account is needed for Twitter ' .
        'module to work.</br>'),
    );

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $key = \Drupal::config('twitter.settings')->get('twitter_consumer_key');
    $secret = \Drupal::config('twitter.settings')->get('twitter_consumer_secret');
    if ($key == '' || $secret == '') {
      $url_admin = Url::fromRoute('twitter.admin_form');
      form_set_error('', t('Please configure your consumer key and secret key at ' .
        '<a href="!url">Twitter settings</a>.', array(
          '!url' => $url_admin,
        )));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $key = \Drupal::config('twitter.settings')->get('twitter_consumer_key');
    $secret = \Drupal::config('twitter.settings')->get('twitter_consumer_secret');
    $twitter = new Twitter($key, $secret);
    $token = $twitter->get_request_token();
    if ($token) {
      $_SESSION['twitter_oauth']['token'] = $token;
      $_SESSION['twitter_oauth']['destination'] = $_GET['q'];
      // Check for the overlay.
      if (\Drupal::moduleHandler()->moduleExists('overlay') && overlay_get_mode() == 'child') {
        overlay_close_dialog($twitter->get_authorize_url($token), array('external' => TRUE));
        overlay_deliver_empty_page();
      }
      else {
        drupal_goto($twitter->get_authorize_url($token));
      }
    }
    else {
      drupal_set_message(t('Could not obtain a valid token from the Twitter API. Please review the configuration.'),
        'error');
    }
  }

}
