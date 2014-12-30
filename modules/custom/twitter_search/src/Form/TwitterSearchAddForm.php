<?php

/**
 * @file
 * Contains \Drupal\twitter_search\Form\TwitterSearchAddForm.
 */

namespace Drupal\twitter_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class TwitterSearchAddForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'twitter_search_add_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['search_string'] = [
      '#type' => 'textfield',
      '#title' => t('Text to search Twitter for'),
      '#default_value' => '',
      '#required' => TRUE,
      '#size' => 50,
      '#maxlength' => 140,
      '#description' => t("Limited to 140 characters."),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save search'),
    ];
    $form['#submit'][] = 'twitter_search_add_form_submit';

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $search = [
      'search' => $form_state->getValue(['search_string']),
      'last_twitter_id' => 0,
      'last_refresh' => 0,
    ];

    // Insert entry into the database.
    \Drupal::database()->insert('twitter_search')->fields($search)->execute();

    drupal_set_message(t('Twitter search text saved.'), 'status');
    $form_state->set(['redirect'], 'admin/config/services/twitter/twitter_search');
    return;
  }

}
