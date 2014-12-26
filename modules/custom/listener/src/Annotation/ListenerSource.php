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
 * Plugin Namespace: Plugin\listener\listenerSource
 *
 * @see \Drupal\listener\Plugin\ListenerSourceManager
 * @see plugin_api
 *
 * @Annotation
 */
class ListenerSource extends Plugin {

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