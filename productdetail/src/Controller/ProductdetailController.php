<?php
/**
 * @file
 * Contains \Drupal\productdetail\Controller\ProductdetailController.
 */
namespace Drupal\productdetail\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface; 
use Drupal\node\Entity\Node;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Drupal\Core\Render\RendererInterface;

/**
 * Class ProductdetailController.
 * 
 */
class ProductdetailController extends ControllerBase {
 

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The Entity Type Manager.
   *
   * @var  \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Constructs a new ProductdetailController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, RendererInterface $renderer) {
    $this->entityTypeManager = $entityTypeManager;
    $this->renderer = $renderer;
  } 
  

  /**
   * {@inheritdoc}
   */
   public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('renderer'),
    );
   } 

   /**
    * {@inheritdoc}
    */
    public function getProductList() {

          // Fetch products from the database       
          $query = $this->entityTypeManager->getStorage('node');
          $query_result = $query->getQuery()
            ->condition('status', 1)
            ->condition('type', 'product_detail')
            ->execute();  

          $result = $query->loadMultiple($query_result);

          $_node_content = [];
          foreach ( $result as $node ) { 

            $body = $node->body->value;
            $title = $node->title->value;  
            $image_alt = isset($node->field_product_image_up)?$node->field_product_image_up->alt:"";   
            $image = isset($node->field_product_image_up->entity)?$node->field_product_image_up->entity->getFileUri():"";   

            $options = ['absolute' => TRUE];
            $url = Url::fromRoute('entity.node.canonical', ['node' => $node->nid->value ], $options);
            $body = mb_strimwidth(strip_tags($body), 0, 100, '...');

            $_node_content[] = [
                                  'title' => $title,
                                  'body' => $body, 
                                  'image' => $image, 
                                  'image_alt' => $image_alt, 
                                  'url' => $url 
                               ];

          }   

          return [       
              '#theme' => 'product_list_template',
              '#data' => $_node_content,
              '#attached' => array(
                'library' => array('productdetail/productdetail'),
              ),
          ];
         
    } 

}