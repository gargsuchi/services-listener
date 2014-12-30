<?php

/**
 * @file
 * Contains \Drupal\twitter\Form\TwitterAdminForm.
 */

namespace Drupal\twitter\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class TwitterAdminForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'twitter_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('twitter.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['twitter_import'] = [
      '#type' => 'checkbox',
      '#title' => t('Import and display the Twitter statuses of site users who have entered their Twitter account information.'),
      '#default_value' => \Drupal::config('twitter.settings')->get('twitter_import'),
    ];

    $options_1 = array(0 => t('Never'));
    $options_2 = array(604800, 2592000, 7776000, 31536000);
    $options_expire = $options_1 + $options_2;
    /*
     * [
        0 => t('Never')
        ] + array_combine([604800, 2592000, 7776000, 31536000], 'format_interval', [
        604800,
        2592000,
        7776000,
        31536000,
      ]),
     */
    $form['twitter_expire'] = [
      '#type' => 'select',
      '#title' => t('Delete old statuses'),
      '#default_value' => \Drupal::config('twitter.settings')->get('twitter_expire'),
      '#options' => $options_expire,
      '#states' => [
        'visible' => [
          ':input[name=twitter_import]' => [
            'checked' => TRUE
            ]
          ]
        ],
    ];

    $form['oauth'] = [
      '#type' => 'fieldset',
      '#title' => t('OAuth Settings'),
      //'#access' => \Drupal::moduleHandler()->moduleExists('oauth_common'),
      '#description' => t('To enable OAuth based access for twitter, you must <a href="@url">register your application</a> with Twitter and add the provided keys here.', [
        '@url' => 'https://dev.twitter.com/apps/new'
        ]),
    ];


     $form['oauth']['callback_url'] = array(
         '#type' => 'item',
         '#title' => t('Callback URL'),
         '#markup' => _url('twitter/oauth', array('absolute' => TRUE)),
       );

    $form['oauth']['twitter_consumer_key'] = [
      '#type' => 'textfield',
      '#title' => t('OAuth Consumer key'),
      '#default_value' => \Drupal::config('twitter.settings')->get('twitter_consumer_key'),
    ];
    $form['oauth']['twitter_consumer_secret'] = [
      '#type' => 'textfield',
      '#title' => t('OAuth Consumer secret'),
      '#default_value' => \Drupal::config('twitter.settings')->get('twitter_consumer_secret'),
    ];

    // Twitter external APIs settings.
    $form['twitter'] = [
      '#type' => 'fieldset',
      '#title' => t('Twitter Settings'),
      '#description' => t('The following settings connect Twitter module with external APIs. ' . 'Change them if, for example, you want to use Identi.ca.'),
    ];
    $form['twitter']['twitter_host'] = [
      '#type' => 'textfield',
      '#title' => t('Twitter host'),
      '#default_value' => \Drupal::config('twitter.settings')->get('twitter_host'),
    ];
    $form['twitter']['twitter_api'] = [
      '#type' => 'textfield',
      '#title' => t('Twitter API'),
      '#default_value' => \Drupal::config('twitter.settings')->get('twitter_api'),
    ];
    $form['twitter']['twitter_search'] = [
      '#type' => 'textfield',
      '#title' => t('Twitter search'),
      '#default_value' => \Drupal::config('twitter.settings')->get('twitter_search'),
    ];
    $form['twitter']['twitter_tinyurl'] = [
      '#type' => 'textfield',
      '#title' => t('TinyURL'),
      '#default_value' => \Drupal::config('twitter.settings')->get('twitter_tinyurl'),
    ];

    return parent::buildForm($form, $form_state);
  }

}
