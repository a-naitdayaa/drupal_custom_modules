<?php

declare(strict_types=1);

namespace Drupal\anytown\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Anytown settings for this site.
 */
class SettingsForm extends ConfigFormBase {
  /**
   * Name for module's configuration object.
   */
  const SETTINGS = 'anytown.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return self::SETTINGS;
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames(): array  {
    return [self::SETTINGS];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) : array {
    $form['display_weather_forecast'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display weather forecast'),
      '#default_value' => $this->config(self::SETTINGS)->get('display_forecast'),
    ];

    $form['location'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ZIP code of market'),
      '#description' => $this->t('Used to determine weekend weather forecast.'),
      '#default_value' => $this->config(self::SETTINGS)->get('location'),
      '#placeholder' => '90210',
    ];

    $form['weather_closures'] = [
      '#type' => 'textarea',
      '#description' => $this->t('List one closure per line.'),
      '#default_value' => $this->config(self::SETTINGS)->get('weather_closures'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) : void {
    parent::validateForm($form, $form_state);

    $location = $form_state->getValue('location');
    $value = filter_var($location, FILTER_VALIDATE_INT);
    if(!$value || strlen((string) $value) !== 5){
      $form_state->setErrorByName('location', $this->t('The location provided is invalid.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) : void {
    $this->config(self::SETTINGS)
      ->set('display_weather_forecast', $form_state->getValue('display_weather_forecast'))
      ->set('location', $form_state->getValue('location'))
      ->set('weather_closures', $form_state->getValue('weather_closures'))
      ->save();

    $this->messenger()->addStatus($this->t('Anytown configuration updated.'));
  }



}
