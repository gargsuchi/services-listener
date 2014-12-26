<?php
/**
 * @file
 * Contains ListenerTargetManager.
 */

namespace Drupal\listener;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * ListenerTarget plugin manager.
 */
class ListenerTargetManager extends DefaultPluginManager {

  /**
   * Constructs an ListenerTargetManager object.
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
    parent::__construct('Plugin/ListenerTarget', $namespaces, $module_handler, 'Drupal\listener\ListenerTargetInterface', 'Drupal\listener\Annotation\ListenerTarget');

    $this->alterInfo('listener_target_info');
    $this->setCacheBackend($cache_backend, 'listener_listener_target');
  }
}