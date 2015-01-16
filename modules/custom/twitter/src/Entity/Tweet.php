<?php

/**
 * @file
 * Contains Drupal\twitter\Entity\Post.
 */

namespace Drupal\twitter\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\twitter\TweetInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Tweet entity.
 *
 * @ingroup twitter
 *
 * @ContentEntityType(
 *   id = "tweet",
 *   label = @Translation("Tweet entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\twitter\Entity\Controller\TweetListController",
 *     "views_data" = "Drupal\twitter\Entity\TweetViewsData",
 *
 *     "form" = {
 *       "add" = "Drupal\twitter\Entity\Form\TweetForm",
 *       "edit" = "Drupal\twitter\Entity\Form\TweetForm",
 *       "delete" = "Drupal\twitter\Entity\Form\TweetDeleteForm",
 *     },
 *     "access" = "Drupal\twitter\TweetAccessControlHandler",
 *   },
 *   base_table = "twitter",
 *   admin_permission = "administer Tweet entity",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "twitter_id" = "twitter_id",
 *   },
 *   links = {
 *     "canonical" = "entity.tweet.canonical",
 *     "edit-form" = "entity.tweet.edit_form",
 *     "delete-form" = "entity.tweet.delete_form"
 *   },
 *   field_ui_base_route = "tweet.settings"
 * )
 */
class Tweet extends ContentEntityBase implements TweetInterface{

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getTwitterId() {
    return $this->get('twitter_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }


  public function getTweetText() {
    return $this->get('text')->value;
  }

  public function getTweetSource() {
    return $this->get('source')->value;
  }

  /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Post entity.'))
      ->setReadOnly(TRUE);

    $fields['twitter_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Tweet entity.'))
      ->setReadOnly(TRUE);

    $fields['screen_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Screen Name'))
      ->setDescription(t('The Screen of the Tweet entity.'))
      ->setReadOnly(TRUE);


    $fields['created_at'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Created At'))
      ->setDescription(t('Where was the tweet created?'))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

      // The text of the contact message.
      $fields['text'] = BaseFieldDefinition::create('string_long')
          ->setLabel(t('Tweet Contents'))
        ->setDescription(t('The tweet text?'))
          ->setRequired(TRUE)
          ->setDisplayOptions('form', array(
              'type' => 'string_textarea',
              'weight' => 0,
              'settings' => array(
                  'rows' => 12,
              ),
          ))
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayOptions('view', array(
              'type' => 'string',
              'weight' => 0,
              'label' => 'above',
          ))
          ->setDisplayConfigurable('view', TRUE);

    $fields['source'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Source'))
      ->setDescription(t('The Source of the Tweet entity.'))
      ->setReadOnly(TRUE);

    $fields['in_reply_to_status_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('In Reply to status ID'))
      ->setDescription(t('In Reply to status ID.'))
      ->setReadOnly(TRUE);

    $fields['in_reply_to_user_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('In Reply to user ID'))
      ->setDescription(t('In Reply to user ID.'))
      ->setReadOnly(TRUE);

    $fields['in_reply_to_screen_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('In reply to screen name'))
      ->setDescription(t('In reply to screen name.'))
      ->setReadOnly(TRUE);

    $fields['truncated'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Truncated'))
      ->setDescription(t('Truncated.'))
      ->setReadOnly(TRUE);



    $fields['created_time'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the tweet was created.'));

    return $fields;
  }
}
