<?php

namespace Drupal\anytown\controller;

use Drupal\Core\Controller\ControllerBase;

class WeatherController extends ControllerBase {

  public function build(): array {
    $build[] = [
      '#type' => 'markup',
      '#markup' => '<p>The weather forecast for this week is sunny with a chance of meatballs.</p>',
    ];

    return $build;
  }
}
