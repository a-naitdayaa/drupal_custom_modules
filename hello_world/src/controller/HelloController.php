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
      '#custom_data' => ['age' => '31', 'DOB' => '3 March 2026'],
      '#custom_string' => 'Hello Block!',
    ];
  }
}
