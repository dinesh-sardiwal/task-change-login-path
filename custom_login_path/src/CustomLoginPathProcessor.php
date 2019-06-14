<?php

namespace Drupal\custom_login_path;

use Drupal\Core\PathProcessor\InboundPathProcessorInterface;
use Drupal\Core\PathProcessor\OutboundPathProcessorInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Symfony\Component\HttpFoundation\Request;


/**
 * Path processor for url_alter_test.
 */
class CustomLoginPathProcessor implements InboundPathProcessorInterface, OutboundPathProcessorInterface {

  /**
   * Implements Drupal\Core\PathProcessor\InboundPathProcessorInterface::processInbound().
   */
  public function processInbound($path, Request $request) {
    $config = \Drupal::config('custom_login_paths.settings');

    // User path
    if ($config->get('custom_path')) {
      $user_path_value = $config->get('path_value');

      // 404 for default user path
      if (preg_match('|^/user(?![^/])|i', $path)) {
        $path = NULL;
      }
      // Get back default user path
      elseif (preg_match('|^/' . urlencode($user_path_value) . '(?![^/])(.*)|', $path, $matches)) {
        $path = '/user' . $matches[1];
      }
    }

    return $path;
  }

  /**
   * Implements Drupal\Core\PathProcessor\OutboundPathProcessorInterface::processOutbound().
   */
  public function processOutbound($path, &$options = array(), Request $request = NULL, BubbleableMetadata $bubbleable_metadata = NULL) {
    $config = \Drupal::config('custom_login_paths.settings');

    // User path
    if ($config->get('custom_path')) {
      $user_path_value = $config->get('path_value');

      // Replace user in path
      if (preg_match('|^/user(?![^/])(.*)|', $path, $matches)) {
        $path = '/' . urlencode($user_path_value) . $matches[1];
      }
    }

    return $path;
  }

}
