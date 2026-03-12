<?php

declare(strict_types=1);

namespace Drupal\rgb_field\Plugin\Field\FieldType;

use Drupal\Core\Field\Attribute\FieldType;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\Exception\MissingDataException;


#[FieldType(
  id: "rgb_field",
  label: new TranslatableMarkup("RGB Color"),
  description: new TranslatableMarkup("Field to store RGB color values."),
  default_widget: "rgb_color_widget_3",
  default_formatter: "rgb_color_formatter",
)]
class RgbItem extends FieldItemBase implements FieldItemInterface {

  /**
   * Defines fields properties.
   *
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition): array
  {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('HEX value'));

    return $properties;
  }

  /**
   * Defines how the field data is stored in the database.
   *
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition): array
  {
    return [
      'columns' => [
        'value' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => TRUE,
        ],
      ]
    ];
  }

  /**
   * @throws MissingDataException
   */
  public function isEmpty(): bool
  {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }
}
