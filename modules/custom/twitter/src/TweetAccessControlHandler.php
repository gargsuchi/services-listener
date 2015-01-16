<?php

/**
 * @file
 * Contains Drupal\account\TweetAccessController.
 */

namespace Drupal\twitter;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Tweet entity.
 *
 * @see \Drupal\twitter\Entity\Tweet.
 */
class TweetAccessControlHandler extends EntityAccessControlHandler
{

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view Tweet entity');
        break;

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit Tweet entity');
        break;

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete Tweet entity');
        break;

    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add Bar entity');
  }
}
