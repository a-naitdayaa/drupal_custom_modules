<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a 'Hello' block.
 */

#[Block(
  id: 'hello_block',
  admin_label: new TranslatableMarkup('Hello block'),
  category: new TranslatableMarkup('Custom'),
)
]
class HelloBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $name = $this->configuration['hello_name'];
    return [
      '#markup' => $this->t('Hello @name!', ['@name' => $name]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $default_config = \Drupal::config('hello_world.settings');
    return [
      'hello_name' => $default_config->get('hello.name') ?? 'World',
    ];
  }
}
