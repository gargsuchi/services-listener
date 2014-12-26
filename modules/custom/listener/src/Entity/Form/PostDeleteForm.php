<?php

/**
 * @file
 * Contains Drupal\listener\Entity\Form\PostDeleteForm.
 */

namespace Drupal\listener\Entity\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting a Post entity.
 *
 * @ingroup listener
 */
class PostDeleteForm extends ContentEntityConfirmFormBase
{

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete entity %name?', array('%name' => $this->entity->label()));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.post.list');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->entity->delete();

    drupal_set_message(
      $this->t('content @type: deleted @label.',
      [
        '@type' => $this->entity->bundle(),
        '@label' => $this->entity->label()
      ]
    ));

    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}
