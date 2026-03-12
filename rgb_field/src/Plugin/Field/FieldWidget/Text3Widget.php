<?php

declare(strict_types=1);

namespace  Drupal\rgb_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\Attribute\FieldWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;


#[FieldWidget(
  id: "rgb_color_widget_3",
  label: new TranslatableMarkup("RGB value as three separate fields"),
  field_types: ["rgb_field"]
)]
class Text3Widget extends WidgetBase {

  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array
  {
    $value = $items[$delta]->value ?? '';

    if (!empty($value)) {
      preg_match_all('@..@', substr($value, 1), $match);
    }
    else {
      $match = [[]];
    }

    $element += [
      '#type' => 'details',
      '#element_validate' => [
        [$this, 'validate']
      ],
    ];

    foreach([
      'R' => $this->t('Red'),
      'G' => $this->t('Green'),
      'B' => $this->t('Blue')]
            as $i => $title) {

      $element[$i] = [
        '#type' => 'textfield',
        '#title' => $title,
        '#size' => 2,
        '#maxlength' => 2,
        '#default_value' => $match[0][$i] ?? '',
        '#attributes' => ['class' => ['rgb-entry']],
        '#description' => $this->t('The 2-digit hexadecimal representation of @color saturation, like "a1" or "ff"', ['@color' => $title]),

      ];

      if ($element['#required']) {
        $element[$i]['#required'] = TRUE;
      }
    }

    return ['value' => $element];
  }


  /**
   * Validate the fields and convert them into a single value as text.
   */
  public function validate(array $element, FormStateInterface $form_state, array $complete_form): void
  {
    // Validate each of the textfield entries.
    $values = [];
    foreach (['R', 'G', 'B'] as $colorfield) {
      $values[$colorfield] = $element[$colorfield]['#value'];

      // If they left any empty, we'll set the value empty and quit.
      if (strlen($values[$colorfield]) == 0) {
        $form_state->setValueForElement($element, '');
        return;
      }

      // If they gave us anything that's not hex, reject it.
      if ((strlen($values[$colorfield]) != 2) || !ctype_xdigit($values[$colorfield])) {
        $form_state->setError($element[$colorfield], $form_state, $this->t("Saturation value must be a 2-digit hexadecimal value between 00 and ff."));
      }


      // Set the value of the entire form element.
      $value = strtolower(sprintf('#%02s%02s%02s', $values['R'], $values['G'], $values['B']));
      $form_state->setValueForElement($element, $value);
    }
  }
}
