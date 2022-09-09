<?php

namespace Drupal\productdetail\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\productdetail\ProductdetailManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Provides a Product Detail QR code settings form.
 */
class ProductdetailForm extends FormBase {

  /**
   * config factory service.
   * 
   */
  protected $config_factory;

  /**
   * Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The Site Location Time.
   *
   * @var \Drupal\productdetail\ProductdetailManagerInterface
   */
  protected $productdetail;

  /**
   * Constructs a new ProductdetailForm.
   *
   * @param \Drupal\productdetail\ProductdetailManagerInterface $productdetail
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $configfactory
   */
  public function __construct(ProductdetailManagerInterface $productdetail, MessengerInterface $messenger, ConfigFactoryInterface $configfactory) {
    $this->productdetail = $productdetail;
    $this->messenger = $messenger;
    $this->config_factory = $configfactory;
  }

  /**
   * {@inheritdoc}
   * 
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $configfactory
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('productdetail.manager'), 
      $container->get('messenger'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {  
    return [  
      'productdetail.adminsettings',  
    ];  
  }  

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'productdetail_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('productdetail.adminsettings');  

    $form['block_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Block Title'), 
      '#default_value' => $config->get('block_title'),  
      '#attributes' => array('class' => array('gcc-select-list')),
      '#required' => TRUE,
    );
 
    $form['block_desc'] = array(
      '#type' => 'textarea',
      '#title' => t('Block Description'), 
      '#default_value' => $config->get('block_desc'),  
      '#attributes' => array('class' => array('gcc-select-list')),
      '#required' => TRUE,
    );

    $form['block_options'] = array(
      '#type' => 'select',
      '#title' => t('Default Show Block in Product Detail Page?'),
      '#options' => $this->productdetail->options_list(),
      '#default_value' => $config->get('block_options'),  
      '#attributes' => array('class' => array('gcc-select-list')),
      '#required' => TRUE,
    );

    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    );
    $form['#validate'][] = '::validateForm';
    return $form;
  }

  /**
   * Validate block form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
     if (empty($form_state->getValue('block_title'))) {
       $form_state->setErrorByName('block_title', $this->t('Please enter block title.'));
     } else if (empty($form_state->getValue('block_desc'))) {
      $form_state->setErrorByName('block_desc', $this->t('Please enter block description.'));
     } else if (empty($form_state->getValue('block_options'))) {
      $form_state->setErrorByName('block_options', $this->t('Please select to show default QR block.'));
     }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) { 
 
    $this->config_factory->getEditable('productdetail.adminsettings')
      ->set('block_title', $form_state->getValue('block_title'))  
      ->set('block_desc', $form_state->getValue('block_desc'))  
      ->set('block_options', $form_state->getValue('block_options'))  
      ->save();   

    drupal_flush_all_caches();

    $output = $this->t('The block configuration options have been saved.'); 
    $this->messenger->addStatus($output);
  }

}
