<?php
/**
 * @file
 * Provides Drupal\listener\ListenerTargetBase.
 */

namespace Drupal\listener;

use Drupal\Component\Plugin\PluginBase;
use SebastianBergmann\Exporter\Exception;
use Drupal\node\Entity\Node;

class ListenerTargetBase extends PluginBase implements ListenerTargetInterface {

  public function getName() {
    return $this->pluginDefinition['name'];
  }

  public function getPrice() {
    return $this->pluginDefinition['price'];
  }

  public function slogan() {
    return t('Best flavor ever.');
  }

  public function match(Node $search) {
    throw new Exception('Match function must be implemented in Listener Target Plugin');
    // Must implement in base
  }

  /**
   * @param Node $search A search configuration object
   */
  public function rebuild(Node $search) {
    $results = $this->match($search);
    $posts = $this->map($results);
    $this->deletePosts($search);
    $this->savePosts($posts);
  }

  /**
   * Deletes entries in Post which are matching the search
   * for the given plugin.
   *
   * @param Node $search
   */
  public function deletePosts(Node $search) {
    // Query
    $query->condition('plugin_id', $this->getBaseId());
    $query->condition('join to matching table?');
  }

  public function savePosts($posts) {

  }

  /**
   * Implement by base class, converts to Listener Post table format.
   *
   * @param $results
   */
  public function map($results) {
    // Implemented by baseclass
    //
  }
}