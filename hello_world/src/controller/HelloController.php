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
      '#theme' => 'hello_block',
      '#custom_data' => ['age' => '22', 'DOB' => '21 Juin 2003'],
      '#custom_string' => 'Hello Everyone, welcome to the hello_world page!',
    ];
  }
}
