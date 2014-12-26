<?php

/**
 * @file
 * Contains Drupal\listener\Controller\DefaultController.
 */

namespace Drupal\listener\Controller;

use Drupal\Core\Controller\ControllerBase;

class DefaultController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello($name) {
    return [
        '#type' => 'markup',
        '#markup' => $this->t('Hello @name!', ['@name' => $name])
    ];
  }
}
