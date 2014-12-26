<?php

/**
 * @file
 * Contains Drupal\listener\Form\PostForm.
 */

namespace Drupal\listener\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

class PostForm extends EntityForm
{

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $post = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $post->label(),
      '#description' => $this->t("Label for the Post."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $post->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\listener\Entity\Post::load',
      ),
      '#disabled' => !$post->isNew(),
    );

    // You will need additional form elements for your custom properties.

    return $form;
  }

  /**
  * {@inheritdoc}
  */
  public function save(array $form, FormStateInterface $form_state) {
    $post = $this->entity;
    $status = $post->save();

    if ($status) {
      drupal_set_message($this->t('Saved the %label Post.', array(
        '%label' => $post->label(),
      )));
    }
    else {
      drupal_set_message($this->t('The %label Post was not saved.', array(
        '%label' => $post->label(),
      )));
    }
    $form_state->setRedirect('entity.post.list');
  }
}
