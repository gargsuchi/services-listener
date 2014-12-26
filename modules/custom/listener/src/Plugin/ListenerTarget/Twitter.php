<?php
/**
 * @file
 * Contains \Drupal\listener\Plugin\ListenerTarget\Twitter.
 */

namespace Drupal\listener\Plugin\ListenerTarget;

use Drupal\listener\ListenerTargetBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'vanilla' flavor.
 *
 * @ListenerTarget(
 *   id = "twitter",
 *   name = @Translation("Vanilla"),
 *   price = 1.75
 * )
 */
class Twitter extends ListenerTargetBase {

  /**
   * @param Node $search A search configuration object
   */
  public function match(Node $search) {
    // Query the twitter entity table for items matching my search.
    // Return the matching items
  }

}