<?php
namespace Drupal\productdetail\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface; 
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface; 
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\node\NodeInterface; 
use SimpleSoftwareIO\QrCode\Generator; 
use Drupal\Core\Url; 
use Drupal\productdetail\ProductdetailManagerInterface;
/**
 * Provides a block to display product detail block with QR Code.
 *
 * @Block(
 *   id = "productdetail_block",
 *   admin_label = @Translation("Product Detail - QR Code"),
 * )
 */
class ProductdetailBlock extends BlockBase implements BlockPluginInterface, ContainerFactoryPluginInterface  {

  /**
   * The Entity Type Manager.
   *
   * @var  \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
   protected $entityTypeManager;
 
  /**
   * The QrCode.
   *
   * @var \SimpleSoftwareIO\QrCode\Generator
   */  
   protected $QrCode_Writer;

   /**
   * The productdetail service.
   *
   * @var Drupal\productdetail\ProductdetailManagerInterface
   */  
  protected $productdetail; 

 
  /**
   * Constructs a new SiteLocationTimeBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param Drupal\Core\Render\RendererInterface $renderer
   * @param Drupal\Core\Routing\CurrentRouteMatch $routematch
   * @param Drupal\productdetail\ProductdetailManagerInterface $productdetail
   */ 
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager, RendererInterface $renderer, CurrentRouteMatch $routematch, ProductdetailManagerInterface $productdetail) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
    $this->renderer = $renderer;
    $this->routematch = $routematch;
    $this->QrCode_Writer = new Generator;
    $this->productdetail = $productdetail;
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
      $container->get('entity_type.manager'),
      $container->get('renderer'),
      $container->get('current_route_match'),
      $container->get('productdetail.manager'),
    );
  } 

  /**
   * {@inheritdoc}
   */
  public function build() { 

      // Load admin settings
      $config = $this->getConfiguration();

      // Load node from URL for product detail page
      $node = $this->routematch->getParameter('node');
      $qr_content = "";
      if ($node instanceof  NodeInterface) {
        $nid = $node->id();
        $options = ['absolute' => TRUE];
        $url = Url::fromRoute('entity.node.canonical', ['node' => $nid ], $options); 
        $qr_content = $this->productdetail->getqrcode($url->toString()); 
      }      

      $config_block = \Drupal::config('productdetail.adminsettings');
      $block_title = $config_block->get('block_title');
      $block_desc = $config_block->get('block_desc');
       
      $_render_data = [
        'block_title' => empty($block_title)?$this->t("Scan here on your mobile"):$block_title,
        'block_desc' => empty($block_desc)?$this->t("To purchase this product on our app to avail exclusive app-only"):$block_desc,
        'block_qrcode' => $qr_content 
      ]; 
       
      return [       
          '#theme' => 'productdetail_block',
          '#data' => $_render_data 
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
    $this->configuration['productdetail_block_settings'] = $form_state->getValue('productdetail_block_settings');
  }
}

 