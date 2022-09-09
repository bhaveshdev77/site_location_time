<?php

namespace Drupal\productdetail;
 
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface; 
use Drupal\Core\Config\ConfigFactoryInterface; 
use SimpleSoftwareIO\QrCode\Generator; 

/**
 * Class ProductdetailManager.
 *
 * @package Drupal\productdetail
 */
class ProductdetailManager implements ProductdetailManagerInterface {

  use StringTranslationTrait;
   

  /**
   * config factory service.
   * 
   */
  protected $config_factory; 

  /**
   * The QrCode.
   *
   * @var \SimpleSoftwareIO\QrCode\Generator
   */  
  protected $qrcode;

  /**
   * {@inheritdoc}
   *  
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configfactory 
   * 
   */
  public function __construct(  ConfigFactoryInterface $configfactory ) { 
    $this->config_factory = $configfactory; 
    $this->qrcode = new Generator;
  }

   /**
   * {@inheritdoc}
   * 
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container 
   * 
   */
  public static function create(ContainerInterface $container) {
    return new static( 
      $container->get('config.factory') 
    );
  }
 
  /**
   * Returns a list of all available render options.
   *
   * @return array
   *   Returns a list of all available render options.
   */
  public function options_list() {
    $timezone_list = [
      'yes' => $this->t('Yes'),
      'no' => $this->t('No') 
    ];
    return $timezone_list;
  } 

  /**
   * Generate and return QR code from content 
   *
   * @return string
   *   Returns  QR code from content 
   */
  public function getqrcode($content_string) {
    $qr_content = $this->qrcode->format('svg')->margin(0)->size(300)->generate($content_string); 
    return $qr_content;
  } 

}
