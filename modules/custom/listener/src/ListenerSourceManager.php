<?php
/**
 * @file
 * Contains ListenerSourceManager.
 */

namespace Drupal\listener;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * ListenerSource plugin manager.
 */
class ListenerSourceManager extends DefaultPluginManager {

  /**
   * Constructs an ListenerSourceManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations,
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/ListenerSource', $namespaces, $module_handler, 'Drupal\listener\ListenerSourceInterface', 'Drupal\listener\Annotation\ListenerSource');

    $this->alterInfo('listener_source_info');
    $this->setCacheBackend($cache_backend, 'listener_listener_source');
  }

  /**
   * Returns an array of definitions keyed by ID with the name as the value
   *
   * @return array;
   */
  public function getDefinitionList() {
    $result = array();
    $definitions = $this->getDefinitions();
    foreach ($definitions as $key => $definition) {
      $result[$definition['id']] = $definition['name']->render();
    }
    return $result;
  }
}