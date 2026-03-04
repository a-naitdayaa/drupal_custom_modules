<?php

declare(strict_types=1);

namespace Drupal\anytown;

/*
 * Forecast Retrieval API Client
 * */

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Logger\RfcLogLevel;
use Drupal\anytown\ForecastClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\ClientInterface;


class ForecastClient implements ForecastClientInterface {
  /**
   * HTTP Client
   * @var \GuzzleHttp\ClientInterface
   *
   */
  protected $http_client;

  /**
   * Logging service
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Construct a forecast API client.
   *
   * @param \GuzzleHttp\ClientInterface $httpClient
   *    Guzzle HTTP client.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   Logger factory service.
   */
  public function __construct(ClientInterface $http_client, LoggerChannelFactoryInterface $logger_factory) {
    $this->http_client = $http_client;
    $this->logger = $logger_factory->get('anytown');
  }


  /**
   * {@inheritDoc}
   */
  public function getForecastData(string $url) : array {
    $this->logger->log(RfcLogLevel::DEBUG, "Fetching data from $url");
    try{
      $response = $this->http_client->get($url);
      $data = json_decode($response->getBody()->getContents());
    }catch(GuzzleException $e){
      $this->logger->log(RfcLogLevel::WARNING, $e->getMessage());
      return NULL;
    }

    $this->logger->log(RfcLogLevel::DEBUG, "Data Fetched successfully");

    $this->logger->log(RfcLogLevel::DEBUG, "Preparing the return array...");

    $forecast = [];
    foreach($data->list as $weather){
      $forecast[$weather->day] = [
        'weekday' => ucfirst($weather->day),
        'description' => array_shift($weather->weather)->description,
        'high_temp' => self::kelvinToFarenheit($weather->main->temp_max),
        'low_temp' => self::kelvinToFarenheit($weather->main->temp_min),
        'icon' => $weather->weather[0]->icon,
      ];
    }

    $this->logger->log(RfcLogLevel::DEBUG, "Retunr array populated successfully");

    return $forecast;
  }

  /**
   * Helper to convert temperature values form Kelvin to Fahrenheit.
   *
   * @param float $kelvin
   *   Temperature in Kelvin.
   *
   * @return float
   *   Temperature in Fahrenheit.
   */
  private static function kelvinToFarenheit(float $kelvin) : float {
    return round(($kelvin - 273.15) * 9 / 5 + 32);
  }

}
