<?php

declare(strict_types=1);

namespace Drupal\rgb_field\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\Attribute\FieldWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\Validator\ConstraintViolationInterface;

#[FieldWidget(
  id: "rgb_color_widget",
  label: new TranslatableMarkup("RGB value as #ffffff"),
  field_types: ["rgb_field"]
)]
class TextWidget extends WidgetBase {

  /**
   * Setting up the Form API array that determines how the field is displayed on an edit form
   *
   *  {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items,   // all values for this field
                              $delta,                          // index of the current value
                              array $element,                  // base element
                              array &$form,                    // the full parent form
                              FormStateInterface $form_state   // current form state
  ): array
  {
    $value = $items[$delta]->value ?? '';
    $element += [
      '#type' => 'textfield',
      '#default_value' => $value,
      '#size' => 7,
      '#maxlength' => 7,
      '#placeholder' => '#ffffff',
      '#element_validate' => [
        [$this, 'validate']
      ],
    ];

    return ['value' => $element];
  }


  /**
   * Defines the error message that will be shown when the field validation fails.
   *
   * {@inheritdoc}
   */
  public function errorElement(array $element, ConstraintViolationInterface $error, array $form, FormStateInterface $form_state)
  {
    return $element['#value'];
  }


  /**
   * Validates the field value and adds an error message if the value is not valid.
   */
  public function validate($element, FormStateInterface $form_state): void
  {
    if(strlen($element['#value']) === 0){
      $form_state->setValueForElement($element, '');
      return;
    }

    if(!Color::validateHex($element['#value'])){
      $form_state->setError($element, $this->t('Invalid hex value'));
      return;
    }
  }
}
