<?php
/**
 * @file
 * Provides Drupal\listener\ListenerTargetInterface
 */

namespace Drupal\listener;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\node\Plugin\Search\NodeSearch;
use Drupal\node\Entity\Node;

/**
 * Defines an interface for Listener targets.
 */
interface ListenerTargetInterface extends PluginInspectionInterface {

  /**
   * Return the name of the ice cream flavor.
   *
   * @return string
   */
  public function getName();

  //@Todo: add a config form to configure which twitter / FB settings to use.
  //public function configurationForm();


  /**
   * Return the price per scoop of the ice cream flavor.
   *
   * @return float
   */
  public function getPrice();

  /**
   * A slogan for the ice cream flavor.
   *
   * @return string
   */
  public function slogan();


  public function match(Node $search);

  /**
   * @param Node $search A search configuration object
   */
  public function rebuild(Node $search);
}