<?php

namespace Drupal\productdetail;

/**
 * Interface ProductdetailManagerInterface.
 *
 * @package Drupal\productdetail
 */
interface ProductdetailManagerInterface {
 
  /**
   * Returns a list of all available render options.
   *
   * @return array
   *   Returns a list of all available render options.
   */
  public function options_list();

  /**
   * Generate and return QR code from content 
   *
   * @return string
   *   Returns  QR code from content 
   */
  public function getqrcode($content_string);

}
