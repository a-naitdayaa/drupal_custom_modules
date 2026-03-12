<?php

declare(strict_types=1);

namespace Drupal\rgb_field\Plugin\Field\FieldFormatter;


use Drupal\Core\Field\Attribute\FieldFormatter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;


#[FieldFormatter(
  id: "rgb_color_formatter",
  label: new TranslatableMarkup("Change the background of the output text"),
  field_types: ["rgb_field"]
)]
class ColorBackgroundFormatter extends FormatterBase {

  /**
   * @inheritDoc
   */
  public function viewElements(FieldItemListInterface $items, $langcode) : array
  {
    $elements = [];

    foreach($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('The content area color has been changed to @code', ['@code' => $item->value]),
        '#attributes' => [
          'style' => 'background-color: ' . $item->value,
        ],
      ];
    }

    return $elements;
  }
}
