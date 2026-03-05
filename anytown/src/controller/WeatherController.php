<?php

namespace Drupal\anytown\controller;

use Drupal\anytown\ForecastClientInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Logger\RfcLogLevel;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WeatherController extends ControllerBase {
  /**
   * Forecast API Client
   * @var \Drupal\anytown\ForecastClientInterface
   *
   * */
  private $forecastClient;


  /**
   * Weather controller constructor
   *
   * @param \Drupal\anytown\ForecastClientInterface $forecastClient
   *   Forecast API client service.
   */
  public function __construct(ForecastClientInterface $forecastClient) {
    $this->forecastClient = $forecastClient;
  }



  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('anytown.forecast_client')
    );
  }

  /**
   * Builds the response.
   */
  public function build(string $style): array {
    $style = (in_array($style, ['short', 'extended'])) ? $style : 'short';

    $url = 'https://raw.githubusercontent.com/DrupalizeMe/module-developer-guide-demo-site/main/backups/weather_forecast.json';
    $data = $this->forecastClient->getForecastData($url);

    $rows = [];
    $highest = 0;
    $lowest = 100;

    if($data){
      foreach($data as $item){
        [
          'weekday' => $weekday,
          'description' => $description,
          'high_temp' => $high_temp,
          'low_temp' => $low_temp,
          'icon' => $icon
        ] = $item;

        $rows[] = [
           $weekday,
          [
            'data' => [
              '#markup' => '<img alt="' . $description . '" src="' . $icon . '" width="200" height="200" />',
            ],
          ],
          [
            'data' => [
              '#markup' => "<em>{$description}</em> with a high temperature of {$high_temp} fahrenheit and a low temperature of {$low_temp} fahrenheit",
            ],
          ],

        ];

        $highest = max($highest, $high_temp);
        $lowest = min($lowest, $low_temp);
      }

      $weather_forecast = [
        '#type' => 'table',
        '#header' => [
          'Day',
          '',
          'Forecast'
        ],
        '#rows' => $rows,
        '#attributes' => [
          'class' => ['weather_page--forecast-table'],
        ],
      ];

      $short_forecast = [
        '#type' => 'markup',
        '#markup' => "The high for the weekend is {$highest} and the low is {$lowest}.",
      ];


    }else{
      $weather_forecast = ['#markup' => '<p>Could not get the weather forecast. Dress for anything.</p>'];
      $short_forecast = NULL;
    }

    $output = [
      '#theme' => 'weather_page',
      '#attached' => [
        'library' => [
          'anytown/forecast',
        ],
      ],
      'weather_intro' => [
        '#markup' => "<p>Check out this weekend's weather forecast and come prepared. The market is mostly outside, and takes place rain or shine.</p>",
      ],
      'weather_forecast' => $weather_forecast,
      'short_forecast' => $short_forecast,
      'weather_closures' => [
        '#theme' => 'item_list',
        '#title' => 'Weather related closures',
        '#items' => [
          'Ice rink closed until winter - please stay off while we prepare it.',
          'Parking behind Apple Lane is still closed from all the rain last weekend.',
        ],
      ],
    ];


    return $output;
  }
}
