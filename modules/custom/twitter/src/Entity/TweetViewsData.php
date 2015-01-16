<?php

/**
 * @file
 * Contains Drupal\listener\Entity\Post.
 */

namespace Drupal\twitter\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides the views data for the Post entity type.
 */
class TweetViewsData extends EntityViewsData implements EntityViewsDataInterface
{

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['twitter']['table']['base'] = array(
      'field' => 'twitter_id',
      'title' => t('Tweet'),
      'help' => t('The Twitter ID.'),
    );

    return $data;
  }


}
