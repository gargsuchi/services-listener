<?php

/**
 * @file
 * Contains Drupal\listener\Entity\Controller\PostListController.
 */

namespace Drupal\twitter\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Url;

/**
 * Provides a list controller for Post entity.
 *
 * @ingroup listener
 */
class TweetListController extends EntityListBuilder
{

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['twitter_id'] = t('Twitter ID');
    $header['source'] = t('Source');
    $header['text'] = t('Tweet Text');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\listener\Entity\Post */
    $row['twitter_id'] = $entity->getTwitterId();
    $row['source'] = $entity->getTweetSource();
    $row['text'] = $entity->getTweetText();
    return $row + parent::buildRow($entity);
  }
}
