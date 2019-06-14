<?php

namespace Drupal\custom_login_path\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
* Implements an example form.
*/
class CustomLoginPathSettingForm extends ConfigFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'custom_login_paths_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'custom_login_paths.settings',
    ];
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_login_paths.settings');

    $form['path_block'] = array(
      '#type' => 'fieldset',
      '#title' => t('Custom user path'),
    );

    $form['path_block']['custom_path'] = array(
      '#type' => 'checkbox',
      '#title' => t('Custom user path'),
      '#default_value' => $config->get('custom_path'),
      '#description' => t('If checked, "user" will be replaced by the following custom path.'),
    );

    $form['path_block']['path_value'] = array(
      '#type' => 'textfield',
      '#title' => t('Replace "user" in user path by'),
      '#default_value' => $config->get('path_value'),
      '#description' => t('This value will replace "user" in user path.'),
    );

    $form['#attached']['library'][] = 'custom_login_path/custom_login_lib';

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save configuration
    $config = \Drupal::service('config.factory')->getEditable('custom_login_paths.settings');
    $config->set('custom_path', $form_state->getValue('custom_path'));
    $config->set('path_value', $form_state->getValue('path_value'));
    $config->save();

    // Rebuild routes
    \Drupal::service('router.builder')->rebuild();

  }

}
