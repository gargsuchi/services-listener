<?php

/**
 * @file
 * Contains Drupal\listener\PostInterface.
 */

namespace Drupal\listener;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a Post entity.
 * @ingroup account
 */
interface PostInterface extends ContentEntityInterface, EntityOwnerInterface
{

  // Add get/set methods for your configuration properties here.
}
