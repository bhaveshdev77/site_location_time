<?php

namespace Drupal\site_location_time;

use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Block\BlockManagerInterface;

/**
 * Class SiteLocationTimeManager.
 *
 * @package Drupal\site_location_time
 */
class SiteLocationTimeManager implements SiteLocationTimeManagerInterface {

  use StringTranslationTrait;
  
  /**
   * Constructs a new Entity plugin manager.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation.
   */

  /**
   * config factory service.
   * 
   */
  protected $config_factory;

  
  /**
   * datetime object
   * 
   */

  protected $datetime;

  /**
   * {@inheritdoc}
   * 
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configfactory
   * @param \Drupal\Core\Datetime\DateFormatter $datetime
   * 
   */
  public function __construct(TranslationInterface $string_translation, ConfigFactoryInterface $configfactory, DateFormatter $datetime) {
    $this->stringTranslation = $string_translation;
    $this->config_factory = $configfactory;
    $this->datetime = $datetime;
  }

   /**
   * {@inheritdoc}
   * 
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container 
   * 
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('translation'), 
      $container->get('config.factory'),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function get_current_location_time() {
   
     /**
      * Get the country, city and timezone and date as per selected timezone from the configured settings options
      */    
    $config = $this->config_factory->getEditable('site_location_time.adminsettings'); 
    $country = empty($config->get('country'))?"India":$config->get('country');
    $city = empty($config->get('city'))?"Kolkata":$config->get('city');
    $timezone = empty($config->get('timezone'))?"Asia/Kolkata":$config->get('timezone');
    $datetime = $this->datetime->format(strtotime('now'), 'custom', 'jS M Y - h:i A', $timezone);
    $datetimefortag  = $this->datetime->format(strtotime('now'), 'custom', 'Y-m-d H:i', $timezone);

    return [ "country"=> $country, "city" => $city, "datetimefortag" => $datetimefortag, "timezone" => $timezone, "datetime" => $datetime ];
    
  }

  /**
   * {@inheritdoc}
   */
  public function timezone_list() {
    $timezone_list = [
      'America/Chicago' => $this->t('America/Chicago'),
      'America/New_York' => $this->t('America/New_York'),
      'Asia/Tokyo' => $this->t('Asia/Tokyo'),
      'Asia/Dubai' => $this->t('Asia/Dubai'),
      'Asia/Kolkata' => $this->t('Asia/Kolkata'),
      'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
      'Europe/Oslo' => $this->t('Europe/Oslo'),
      'Europe/London' => $this->t('Europe/London') 
    ];
    return $timezone_list;
  } 

}
