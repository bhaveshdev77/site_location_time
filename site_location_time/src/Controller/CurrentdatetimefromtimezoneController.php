<?php
/**
 * @file
 * Contains \Drupal\site_location_time\Controller\CurrentdatetimefromtimezoneController.
 */
namespace Drupal\site_location_time\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\site_location_time\SiteLocationTimeManagerInterface;

/**
 * Class CurrentdatetimefromtimezoneController.
 * 
 */
class CurrentdatetimefromtimezoneController extends ControllerBase {

  /**
   * The Site Location Time.
   *
    * @param \Drupal\site_location_time\SiteLocationTimeManagerInterface $sitelocationtime
   *   The Site Location Time Manager. 
   */
  protected $sitelocationtime;

  /**
   * Constructs a JsonResponse object.
   *
   * @param Symfony\Component\HttpFoundation\JsonResponse $jsonresponse 
   */
  public function __construct(SiteLocationTimeManagerInterface $sitelocationtime) {
    $this->sitelocationtime = $sitelocationtime; 
  }

  /**
   * {@inheritdoc}
   */
   public static function create(ContainerInterface $container) {
    return new static(
      $container->get('site_location_time.manager')
    );
   }

   /**
    * {@inheritdoc}
    */
    public function getUpdatedDateTimeCallback() {
        // Fetch latest time and date as per timezone selection and return json
        $array_variables = $this->sitelocationtime->get_current_location_time(); 
        return new JsonResponse($array_variables);
    }


}