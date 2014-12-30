<?php

/**
 * @file
 * Contains \Drupal\twitter_search\Form\TwitterSearchDeleteConfirm.
 */

namespace Drupal\twitter_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class TwitterSearchDeleteConfirm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'twitter_search_delete_confirm';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $twitter_search_id = arg(6);

    $sql = "SELECT search
          FROM {twitter_search}
          WHERE twitter_search_id = %d";
    $search_string = db_query("SELECT search
          FROM {twitter_search}
          WHERE twitter_search_id = :twitter_search_id", [
      ':twitter_search_id' => $twitter_search_id
      ])->fetchField();

    $form['twitter_search_id'] = [
      '#type' => 'value',
      '#value' => $twitter_search_id,
    ];
    $form['search_string'] = [
      '#type' => 'value',
      '#value' => $search_string,
    ];

    $message = t('Are you sure you want to delete the Twitter search "%search"?', [
      '%search' => $search_string
      ]);
    $caption = '<p>' . t('This action cannot be undone.') . '</p>';

    return confirm_form($form, $message, 'admin/config/services/twitter/twitter_search', $caption, t('Delete'));
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $twitter_search_id = $form_state->getValue(['twitter_search_id']);
    $search_string = check_plain($form_state->getValue(['search_string']));

    $result = db_delete('twitter_search')
      ->condition('twitter_search_id', $twitter_search_id)
      ->execute();

    drupal_set_message(t('The search "%search" has been deleted.', [
      '%search' => $search_string
      ]));

    $form_state->set(['redirect'], 'admin/config/services/twitter/twitter_search');
    return;
  }

}
