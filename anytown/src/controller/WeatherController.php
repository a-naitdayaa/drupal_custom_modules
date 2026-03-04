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
  public function build(): array {
    $url = 'https://raw.githubusercontent.com/DrupalizeMe/module-developer-guide-demo-site/main/backups/weather_forecast.json';
    $data = $this->forecastClient->getForecastData($url);

    if($data){
      $forecast = '<ul>';
      foreach($data as $item){
        $day = $item['day'];
        $description = $item['description'];
        $high_temp = $item['high_temp'];
        $low_temp = $item['low_temp'];

        $forecast .= "<li>$day will have <em>$description</em> with a high temperature of <b>$high_temp</b> fahrenheit, and a low temperature of <b>$low_temp</b> fahrenheit.</li>";
      }

      $forecast .= '</ul>';
    }else{
      $forecast = '<p>Could not get the weather forecast. Dress for anything.</p>';
    }

    $output = "<p>Check out this weekend's weather forecast and come prepared. The market is mostly outside, and takes place rain or shine.</p>";
    $output .= $forecast;
    $output .= '<h3>Weather related closures</h3></h3><ul><li>Ice rink closed until winter - please stay off while we prepare it.</li><li>Parking behind Apple Lane is still closed from all the rain last week.</li></ul>';

    return [
      '#markup' => $output,
    ];
  }
}
