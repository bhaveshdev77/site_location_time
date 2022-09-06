<?php
namespace Drupal\site_location_time\Plugin\Filter;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\Core\Form\FormStateInterface; 
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Block\BlockManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface; 

/**
 * Provides a filter to replace [sitelocation_datetime] by the block content in CKEditor 
 *
 * @Filter(
 *   id = "filter_site_location_time",
 *   title = @Translation("Site Location and DateTime Filter"),
 *   description = @Translation("Help to filter and render block for site location and datetime view as per selected timezone."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class FilterLocationDateTime extends FilterBase implements ContainerFactoryPluginInterface   {

   use StringTranslationTrait;  
  
  /**
   * The Block Manager
   *
    * @param  Drupal\Core\Block\BlockManagerInterface $blockmanager
   *   The Site Location Time Manager. 
   */
  protected $blockmanager;

   /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;
 

  /**
   * Constructs a object.
   *
   * @param Drupal\Core\Block\BlockManagerInterface
   */
  /**
   * Constructs a new FilterLocationDateTime object.
   * 
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Messenger\MessengerInterface $blockmanager
   * @param Drupal\Core\Render\RendererInterface $renderer
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, BlockManagerInterface  $blockmanager, RendererInterface $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->blockmanager = $blockmanager; 
    $this->renderer = $renderer;
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
        $container->get('plugin.manager.block'),
        $container->get('renderer')
      ); 
   }

    
  /**
   * {@inheritdoc}
   * This function will replace [sitelocation_datetime] by site location block content in CKEditor
   * 
   * @param string $text
   *   The text from the CKEditor.
   * @param mixed $langcode
   *   Language code.
   */
  public function process($text, $langcode) {

    if($this->settings['filter_location_datetime']) {
        
         // Load block by ID "site_location_time_block" and replace content for [sitelocation_datetime] filter tag
         $plugin_block = $this->blockmanager->createInstance('site_location_time_block',[]);
         $render = $plugin_block->build();
         $block_markup = $this->renderer->renderRoot($render);  
         $text = str_replace('[sitelocation_datetime]', $block_markup, $text);
        
    }

    $result = new FilterProcessResult($text); 

    return $result;
  }

  /**
   * {@inheritdoc}
   * 
   * @param array $form
   *   Array of default fields
   * @param \Drupal\Core\Form\FormStateInterface $form_state 
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    $form['filter_location_datetime'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Show Site Location and DateTime?'),
      '#default_value' => $this->settings['filter_location_datetime'],
      '#description' => $this->t('Display a site location and datetime as per selected timezone.'),
    );
    return $form;
    
  }
}