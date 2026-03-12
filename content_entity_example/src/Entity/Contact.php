<?php

declare(strict_types=1);

namespace Drupal\content_entity_example\Entity;

use Drupal\content_entity_example\ContactInterface;
use Drupal\Core\Entity\Attribute\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Exception\UnsupportedEntityTypeDefinitionException;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\user\EntityOwnerTrait;

#[ContentEntityType(
  id: 'content_entity_example_contact',
  label: new TranslatableMarkup('Contact entity'),
  entity_keys: [
    'id' => 'id',
    'label' => 'name',
    'uuid' => 'uuid',
    'owner' => 'user_id',
  ],
  handlers: [
    'list_builder' => 'Drupal\content_entity_example\Entity\Controller\ContactListBuilder',
    'views_data' => 'Drupal\views\EntityViewsData',
    'access' => 'Drupal\content_entity_example\ContactAccessControlHandler',
    'form' => [
      'add' => 'Drupal\content_entity_example\Form\ContactForm',
      'edit' => 'Drupal\content_entity_example\Form\ContactForm',
      'delete' => 'Drupal\Core\Entity\ContentEntityDelete',
    ],
    'route_provider' => [
      'html' => 'Drupal\Core\Entity\Routing\AdminHtmlRouteProvider',
    ],
  ],
  links: [
    'canonical' => '/content_entity_example_contact/{content_entity_example_contact}',
    'add-form' => '/content_entity_example_contact/add',
    'edit-form' => '/content_entity_example_contact/{content_entity_example_contact}/edit',
    'delete-form' => '/contact/{content_entity_example_contact}/delete',
    'collection' => '/content_entity_example_contact/list',
  ],
  admin_permission: 'administer contact entity',
  base_table: 'contact',
  field_ui_base_route: 'content_entity_example.contact_settings',
  list_cache_contexts: ['user'],
)]
class Contact extends ContentEntityBase implements ContactInterface{
  use EntityChangedTrait;
  use EntityOwnerTrait;


  /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public function preSave(EntityStorageInterface $storage): void
  {
    parent::preSave($storage);
    if(!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   *
   * Defines field properties of the entity Content.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   *
   * @throws UnsupportedEntityTypeDefinitionException
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array
  {
    // In ContentEntityBase, baseFieldDefinitions defines fields
    // id, uuid, revision, langcode, and bundle.
    $fields = parent::baseFieldDefinitions($entity_type);

    // Adding any default base field definitions from EntityOwnerTrait.
    // When using any other entity traits in the code,
    // we should check to see if the trait provides any default base fields and make sure we call that method.
    $fields += static::ownerBaseFieldDefinitions($entity_type);

    // Name field for the contact.
    // We set display options for the view as well as the form.
    // Users with correct privileges can change the view and edit configuration.
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the contact entity.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue(NULL)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -6,
      ])
      // Allowing site administrators to change display settings in the UI.
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First name'))
      ->setDescription(t('The first name of the contact entity.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue(NULL)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    ;

    // Owner field of the contact.
    // Entity reference field, holds the reference to the user object.
    // The view shows the username field of the user.
    // The form presents an autocomplete field for the username.
    // The key in $fields['user_id'] needs to match whatever is set in
    // the 'owner' entity key in the attribute, in order for the
    // EntityOwnerTrait to work as designed.
    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User Name'))
      ->setDescription(t('The name of the associated user.'))
      ->setSettings([
        'target_type' => 'user',
        'handler' => 'default',    // controls selection logic (default handler allows selecting any user).
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',  // matches any part of the username, not just beginning.
          'match_limit' => 10,   // shows up to 10 suggestions
          'size' => '60',
          'placeholder' => '',
        ],
        'weight' => -3,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => -3,
        ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['role'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Role'))
      ->setDescription(t('The role of the contact entity.'))
      ->setSettings([
        'allowed_values' => [
          'administrator' => 'administrator',
          'user' => 'user',
        ],
      ])
      ->setDefaultValue('user')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight'  => -2
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }
}
