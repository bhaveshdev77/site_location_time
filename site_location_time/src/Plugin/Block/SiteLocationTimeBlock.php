<?php
namespace Drupal\site_location_time\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\site_location_time\SiteLocationTimeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface; 
/**
 * Provides a block to display site location and date and time as per selected timezone from admin.
 *
 * @Block(
 *   id = "site_location_time_block",
 *   admin_label = @Translation("Site Location & DateTime"),
 * )
 */
class SiteLocationTimeBlock extends BlockBase implements ContainerFactoryPluginInterface  {

  
  /**
   * The Site Location Time.
   *
   * @var \Drupal\site_location_time\SiteLocationTimeManagerInterface
   */
  protected $sitelocationtime;


  /**
   * Constructs a new SiteLocationTimeBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\site_location_time\SiteLocationTimeManagerInterface $sitelocationtime
   *   The Site Location Time Manager. 
   */ 
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SiteLocationTimeManagerInterface $sitelocationtime) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->sitelocationtime = $sitelocationtime;
  }

  /**
   * {@inheritdoc}
   * 
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('site_location_time.manager')
    );
  } 

  /**
   * {@inheritdoc}
   */
  public function build() { 

    $array_variables = $this->sitelocationtime->get_current_location_time();  
    return [       
        '#theme' => 'site_location_time_block',
        '#data' => $array_variables,
        '#attached' => array(
          'library' => array('site_location_time/site-location-time'),
        ),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['site_location_time_block_settings'] = $form_state->getValue('site_location_time_block_settings');
  }
}

 