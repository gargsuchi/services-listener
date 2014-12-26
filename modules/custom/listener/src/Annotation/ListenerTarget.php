<?php
/**
 * @file
 * Contains \Drupal\listener\Annotation\Flavor.
 */

namespace Drupal\listener\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a flavor item annotation object.
 *
 * Plugin Namespace: Plugin\listener\listenerTarget
 *
 * @see \Drupal\listener\Plugin\ListenerTargetManager
 * @see plugin_api
 *
 * @Annotation
 */
class ListenerTarget extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The name of the flavor.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $name;

  /**
   * The price of one scoop of the flavor in dollars.
   *
   * @var float
   */
  public $price;

}