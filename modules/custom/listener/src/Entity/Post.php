<?php

/**
 * @file
 * Contains Drupal\listener\Entity\Post.
 */

namespace Drupal\listener\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\listener\PostInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Post entity.
 *
 * @ingroup listener
 *
 * @ContentEntityType(
 *   id = "post",
 *   label = @Translation("Post entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\listener\Entity\Controller\PostListController",
 *     "views_data" = "Drupal\listener\Entity\PostViewsData",
 *
 *     "form" = {
 *       "add" = "Drupal\listener\Entity\Form\PostForm",
 *       "edit" = "Drupal\listener\Entity\Form\PostForm",
 *       "delete" = "Drupal\listener\Entity\Form\PostDeleteForm",
 *     },
 *     "access" = "Drupal\listener\PostAccessControlHandler",
 *   },
 *   base_table = "post",
 *   admin_permission = "administer Post entity",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "entity.post.canonical",
 *     "edit-form" = "entity.post.edit_form",
 *     "delete-form" = "entity.post.delete_form"
 *   },
 *   field_ui_base_route = "post.settings"
 * )
 */
class Post extends ContentEntityBase implements PostInterface
{

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
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->source_id;
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


  public function getPostText() {
    return $this->get('postText')->value;
  }

  public function getPostScore() {
    return $this->get('score')->value;
  }

  public function getSourceName() {
    $plugin_options = \Drupal::service('plugin.manager.listener_source')->getDefinitionList();
    return $plugin_options[$this->get('plugin_id')->value];
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Post entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Post entity.'))
      ->setReadOnly(TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of the Post entity author.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Post entity.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 50,
        'text_processing' => 0,
      ))
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

    $plugin_options = \Drupal::service('plugin.manager.listener_source')->getDefinitionList();
    //kpr($plugin_options);


    $fields['plugin_id'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Plugin Id'))
      ->setDescription(t('Source plugin'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -4,
      ))
      ->setSetting('allowed_values', $plugin_options)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

      // The text of the contact message.
      $fields['postText'] = BaseFieldDefinition::create('string_long')
          ->setLabel(t('Post Contents'))
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

    $fields['score'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Score'))
      ->setDescription(t('The score of the post entity.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 10,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'float',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'float',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);





    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code of Post entity.'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }
}
