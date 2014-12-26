<?php

/**
 * @file
 * Contains Drupal\account\PostAccessController.
 */

namespace Drupal\listener;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Post entity.
 *
 * @see \Drupal\listener\Entity\Post.
 */
class PostAccessControlHandler extends EntityAccessControlHandler
{

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view Post entity');
        break;

      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit Post entity');
        break;

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete Post entity');
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
