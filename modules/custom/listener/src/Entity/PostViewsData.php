<?php

/**
 * @file
 * Contains Drupal\listener\Entity\Post.
 */

namespace Drupal\listener\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides the views data for the Post entity type.
 */
class PostViewsData extends EntityViewsData implements EntityViewsDataInterface
{

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['post']['table']['base'] = array(
      'field' => 'id',
      'title' => t('Post'),
      'help' => t('The post entity ID.'),
    );

    return $data;
  }


}
