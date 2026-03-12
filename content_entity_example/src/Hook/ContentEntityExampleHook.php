<?php

declare(strict_types=1);

namespace Drupal\content_entity_example\Hook;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

class ContentEntityExampleHook {

  protected $logger;
  public function __construct(LoggerChannelFactoryInterface $logger_factory) {
    $this->logger = $logger_factory;
  }

  #[Hook('entity_base_field_info')]
  public function content_entity_example_entity_base_field_info(EntityTypeInterface $entity_type) : array {
    $this->logger->get('content_entity_example')->debug('hook_entity_base_field_info() is firing for entity type: @entity_type', ['@entity_type' => $entity_type->id()]);

    $fields = [];
    if($entity_type->id() === 'content_entity_example_contact') {
      $fields['additional_field'] = BaseFieldDefinition::create('string')
        ->setLabel('Additional Field')
        ->setDescription('This is an additional field added via hook_entity_base_field_info().')
        ->setRequired(FALSE)
        ->setDisplayOptions('view', [
          'label' => 'above',
          'type' => 'string',
          'weight' => 0,
        ])
        ->setDisplayOptions('form', [
          'type' => 'string_textfield',
          'weight' => 0,
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);
    }

    return $fields;
  }

}
