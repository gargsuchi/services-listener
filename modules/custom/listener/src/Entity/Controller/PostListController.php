<?php

/**
 * @file
 * Contains Drupal\listener\Entity\Controller\PostListController.
 */

namespace Drupal\listener\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Url;

/**
 * Provides a list controller for Post entity.
 *
 * @ingroup listener
 */
class PostListController extends EntityListBuilder
{

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = t('PostID');
    $header['name'] = t('Name');
    $header['source'] = t('Source');
    $header['postText'] = t('Post Text');
    $header['score'] = t('Post score');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\listener\Entity\Post */
    $row['id'] = $entity->id();
    $row['name'] = \Drupal::l(
        $this->getLabel($entity),
        new Url(
          'entity.post.edit_form', array(
            'post' => $entity->id(),
        )
      )
    );
    $row['source'] = $entity->getSourceName();
    $row['postText'] = $entity->getPostText();
    $row['score'] = $entity->getPostText();
    return $row + parent::buildRow($entity);
  }
}
