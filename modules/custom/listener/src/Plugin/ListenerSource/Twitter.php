<?php
/**
 * @file
 * Contains \Drupal\listener\Plugin\ListenerSource\Twitter.
 */

namespace Drupal\listener\Plugin\ListenerSource;

use Drupal\listener\ListenerSourceBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'vanilla' flavor.
 *
 * @ListenerSource(
 *   id = "twitter",
 *   name = @Translation("Vanilla"),
 *   price = 1.75
 * )
 */
class Twitter extends ListenerSourceBase {

  /**
   * @param Node $search A search configuration object
   */
  public function match(Node $search) {
    // Query the twitter entity table for items matching my search.
    // Return the matching items
  }

}