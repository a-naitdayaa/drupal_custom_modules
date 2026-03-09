<?php

declare(strict_types=1);

namespace Drupal\anytown\Form;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Form\FormBase;


class NodeSelectForm extends FormBase {
  use StringTranslationTrait;

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  public function getFormId(): string {
    return 'anytown.node_select_form';
  }

  public  function buildForm(array $form, FormStateInterface $form_state) {
    $form['node_select'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Select a node'),
      '#target_type' => 'node',
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }


  /**
   * @throws InvalidPluginDefinitionException
   * @throws PluginNotFoundException
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nid = $form_state->getValue('node_select');

    $node = $this->entityTypeManager
      ->getStorage('node')
      ->load($nid);

    \Drupal::state()->set('anytown.selected_node', $node->id());
  }
}
