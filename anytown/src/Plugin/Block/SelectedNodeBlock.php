<?php

namespace Drupal\anytown\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block that shows the selected node title.
 *
 * @Block(
 *   id = "anytown_selected_node_block",
 *   admin_label = @Translation("Selected Node Title")
 * )
 */
class SelectedNodeBlock extends BlockBase implements ContainerFactoryPluginInterface{

  protected $entityTypeManager;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  public function build() {
    $nid = \Drupal::state()->get('anytown.selected_node');

    if (!$nid) {
      return [
        '#markup' => 'No node selected yet.'
      ];
    }

    $node = $this->entityTypeManager
      ->getStorage('node')
      ->load($nid);

    if (!$node) {
      return [
        '#markup' => 'Node not found.'
      ];
    }

    return [
      '#markup' => $node->getTitle(),
    ];

  }


}
