<?php

namespace Drupal\site_location_time;

/**
 * Interface SiteLocationTimeManagerInterface.
 *
 * @package Drupal\site_location_time
 */
interface SiteLocationTimeManagerInterface {

  /**
   * Return county, city and timezone of the site.
   *
   * @return array
   *   Returns the converted amount.
   */
  public function get_current_location_time();

  /**
   * Returns a list of all available timezone.
   *
   * @return array
   *   Returns a list of all available timezone.
   */
  public function timezone_list();

}
