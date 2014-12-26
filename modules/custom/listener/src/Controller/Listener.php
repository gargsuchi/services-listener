<?php

/**
 * @file
 * Contains Drupal\listener\Controller\Listener.
 */

namespace Drupal\listener\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\listener\ListenerSourceManager;
use Drupal\node\Entity\Node;

class Listener extends ControllerBase {

  /**
   * Rebuild.
   *
   * @return string
   *   Return Hello string.
   */
  public function rebuild($search = '') {

    $query = \Drupal::service('entity.query')->get('node');

    $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('nid', $search)
      ->condition('type', 'search');

    $nids = $query->execute();
    if (!$nids) {
      return [
        '#type' => 'markup',
        '#markup' => $this->t('Could not find that search configuration. Sorry')
      ];
    }

    $searchObject = entity_load('node', $nids[1]);

    // Get all plugins
    // Tell them to rebuild using this search object.
    // @todo: add a queue / batch

    $pm = '';
    $pm = \Drupal::service('plugin.manager.listener_source');
    $definitions = $pm->getDefinitions();
    foreach($definitions as $definition) {
      $plugin = $pm->createInstance($definition['id']);
      $results[] = $plugin->rebuild($searchObject);
    }

    kpr($results);
    return [
        '#type' => 'markup',
        '#markup' => $this->t('Implement method: rebuild')
    ];
  }

  public function index() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: index')
    ];
  }
}
