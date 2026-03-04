<?php

declare(strict_types=1);

namespace Drupal\anytown;

/*
 * Forecast Retrieval API Client Interface
 * */
interface ForecastClientInterface {
  /**
   * Get the current Forecast
   *
   * @param string $url
   * Url used to retrieve forecast data
   *
   * @return array | null
   * Array containing the formatted forecast data or null
   * */
  public function getForecastData(string $url) : ?array;
}
