<?php

declare(strict_types=1);

namespace Drupal\anytown\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;

class AnytownFormAlters {

  /**
   * Implements hook_form_FORM_ID_alter() for the registration form.
   *
   * FORM_ID == user_register_form
   */
  #[Hook('form_user_register_form_alter')]
  public function FormUserRegistrationAlter(array &$form, FormStateInterface $form_state) : void {
    //\Drupal::logger('anytown')->debug('form alter is firing');

    $form['#validate'][] = [self::class, 'validateFormUserRegistrationForm'];

    $form['terms_of_use'] = [
      '#type' => 'fieldset',
      '#title' => t('Anytown Terms and Conditions of Use'),
      '#weight' => 10,
      '#access' =>  !\Drupal::currentUser()->hasPermission('administer users'),
    ];

    $form['terms_of_use']['terms_of_use_data'] = [
      '#type' => 'markup',
      '#markup' => '<p>By checking the box below you agree to our terms of use. Whatever that might be. ¯\_(ツ)_/¯</p>',
    ];

    $form['terms_of_use']['terms_of_use_checkbox'] = [
      '#type' => 'checkbox',
      '#title' => t('I agree with the terms above'),
      '#required' => TRUE,
    ];
  }

  /**
   * Custom validation handler for the user registration form.
   */
  public static function validateFormUserRegistrationForm(&$form, FormStateInterface $form_state)
  {
    if ($form_state->getValue('mail') === 'anytown@example.com') {
      $form_state->setErrorByName('name', t('The username "anytown" is invalid. Please choose a different name.'));
    }
  }
}
