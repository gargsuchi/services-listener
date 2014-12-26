<?php

/**
 * @file
 * Contains Drupal\listener\Form\PostDeleteForm.
 */

namespace Drupal\listener\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Builds the form to delete a Post.
 */
class PostDeleteForm extends EntityConfirmFormBase
{

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete %name?', array('%name' => $this->entity->label()));
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
    return $this->t('Delete');
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
