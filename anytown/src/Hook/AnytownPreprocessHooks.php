<?php

declare(strict_types=1);

namespace Drupal\anytown\Hook;

use Drupal\Core\Hook\Attribute\Hook;

class AnytownPreprocessHooks{

  #[Hook('preprocess_menu')]
  public function anytown_preprocess_menu(array &$variables) : void {
    \Drupal::logger('anytown')->debug('Menu preprocessing is firing');

    //defining class attribute for each menu item in the main menu
    if($variables['menu_name'] === 'main'){
      foreach($variables['items'] as &$item){
        $item['attributes']->addClass('my-custom-class');
      }
    }

    $variables['#attached']['library'][] = 'anytown/menu';

    //dump($variables);

    \Drupal::logger('anytown')->debug('Menu preprocessing terminated successfully');
  }

  #[Hook('preprocess_page')]
  public function anytown_preprocess_page(array &$variables) : void {
    \Drupal::logger('anytown')->debug('Page preprocessing is firing');
    $variables['#attached']['html_head'][] = [
      [
        '#type' => 'html_tag',
        '#tag' => 'meta',
        '#attributes' => [
          'name' => 'viewport',
          'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no',
        ],
      ],
      'viewport',
    ];
    \Drupal::logger('anytown')->debug('Page preprocessing terminated successfully');

  }

  #[Hook('preprocess_block')]
  public function anytown_preprocess_block(array &$variables) {
    \Drupal::logger('anytown')->debug('Block preprocessing is firing');
    if($variables['plugin_id'] === 'system_branding_block'){
      $variables['content']['site_logo']['#uri'] = 'https://static.cdnlogo.com/logos/d/88/drupal-wordmark.svg';
    }
    dump($variables['content']);
    \Drupal::logger('anytown')->debug('Block preprocessing terminated successfully');
  }


  }

