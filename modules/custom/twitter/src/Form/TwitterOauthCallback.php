<?php

/**
 * @file
 * Contains \Drupal\twitter\Form\TwitterOauthCallback.
 */

namespace Drupal\twitter\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

abstract class TwitterOauthCallback extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'twitter_oauth_callback';
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    if (isset($_GET['denied']) || empty($_GET['oauth_token'])) {
      drupal_set_message(t('The connection to Twitter failed. Please try again.'), 'error');
      $user = \Drupal::currentUser();
      if ($user->uid) {
        // User is logged in, was attempting to OAuth a Twitter account.
        drupal_goto('admin/config/services/twitter');
      }
      else {
        // Anonymous user, redirect to front page.
        drupal_goto('<front>');
      }
    }
    $form_state->setValue(['oauth_token'], $_GET['oauth_token']);
    \Drupal::formBuilder()->submitForm('twitter_oauth_callback_form', $form_state);
  }

}
