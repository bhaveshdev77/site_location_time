<?php

namespace Drupal\site_location_time\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\site_location_time\SiteLocationTimeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Provides a Site Location Time form.
 */
class SiteLocationTimeForm extends FormBase {

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
   * @var \Drupal\site_location_time\SiteLocationTimeManagerInterface
   */
  protected $sitelocationtime;

  /**
   * Constructs a new SiteLocationTimeForm.
   *
   * @param \Drupal\site_location_time\SiteLocationTimeManagerInterface $site_location_time
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $configfactory
   */
  public function __construct(SiteLocationTimeManagerInterface $site_location_time, MessengerInterface $messenger, ConfigFactoryInterface $configfactory) {
    $this->admin_form_config = $site_location_time;
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
      $container->get('site_location_time.manager'), 
      $container->get('messenger'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {  
    return [  
      'site_location_time.adminsettings',  
    ];  
  }  

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'site_location_time_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('site_location_time.adminsettings');  

    $form['country'] = array(
      '#type' => 'textfield',
      '#title' => t('Country'), 
      '#default_value' => $config->get('country'),  
      '#attributes' => array('class' => array('gcc-select-list')),
      '#required' => TRUE,
    );
 
    $form['city'] = array(
      '#type' => 'textfield',
      '#title' => t('City'), 
      '#default_value' => $config->get('city'),  
      '#attributes' => array('class' => array('gcc-select-list')),
      '#required' => TRUE,
    );

    $form['timezone'] = array(
      '#type' => 'select',
      '#title' => t('Timezone'),
      '#options' => $this->admin_form_config->timezone_list(),
      '#default_value' => $config->get('timezone'),  
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
   * Checks from currency is not equal to converted currency.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
     if (empty($form_state->getValue('city'))) {
       $form_state->setErrorByName('city', $this->t('Please enter city.'));
     } else if (empty($form_state->getValue('country'))) {
      $form_state->setErrorByName('country', $this->t('Please enter country.'));
     } else if (empty($form_state->getValue('timezone'))) {
      $form_state->setErrorByName('timezone', $this->t('Please enter timezone.'));
     }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) { 
 
    $this->config_factory->getEditable('site_location_time.adminsettings')
      ->set('country', $form_state->getValue('country'))  
      ->set('city', $form_state->getValue('city'))  
      ->set('timezone', $form_state->getValue('timezone'))  
      ->save();  

    $output = $this->t('The configuration options have been saved.'); 
    $this->messenger->addStatus($output);
  }

}
