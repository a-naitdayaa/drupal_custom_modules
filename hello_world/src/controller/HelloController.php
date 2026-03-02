<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Defines HelloController Class
 *
 * @package Drupal\HelloWorld\Controller
 */
class HelloController extends ControllerBase
{
  public function content(){
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello world !'),
    ];
  }
}
