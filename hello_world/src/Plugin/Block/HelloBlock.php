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
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello world !'),
    ];
  }
}
