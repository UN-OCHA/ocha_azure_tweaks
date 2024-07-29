<?php

namespace Drupal\ocha_azure_tweaks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Url;
use Drupal\openid_connect\OpenIDConnectClaims;
use Drupal\openid_connect\OpenIDConnectSessionInterface;

/**
 * Returns responses for OpenID Connect Windows AAD module routes.
 */
class AuthController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The OpenID Connect claims.
   *
   * @var \Drupal\openid_connect\OpenIDConnectClaims
   */
  protected $claims;

  /**
   * The OpenID Connect session service.
   *
   * @var \Drupal\openid_connect\OpenIDConnectSessionInterface
   */
  protected $session;

  /**
   * Redirect the user registration page.
   */
  public function redirectRegister() {
    $client_id = $this->config('ocha_azure_tweaks.settings')->get('openid_register_client');

    $container = \Drupal::getContainer();

    $this->entityTypeManager = $container->get('entity_type.manager');
    $this->claims = $container->get('openid_connect.claims');
    $this->session = $container->get('openid_connect.session');

    $client = $this->entityTypeManager->getStorage('openid_connect_client')->loadByProperties(['id' => $client_id])[$client_id];
    $plugin = $client->getPlugin();
    $scopes = $this->claims->getScopes($plugin);
    $this->session->saveOp('login');
    $response = $plugin->authorize($scopes);

    return $response->send();
  }

  /**
   * Redirect the password reset page.
   */
  public function redirectResetPassword() {
    $client_id = $this->config('ocha_azure_tweaks.settings')->get('openid_reset_client');

    $container = \Drupal::getContainer();

    $this->entityTypeManager = $container->get('entity_type.manager');
    $this->claims = $container->get('openid_connect.claims');
    $this->session = $container->get('openid_connect.session');

    $client = $this->entityTypeManager->getStorage('openid_connect_client')->loadByProperties(['id' => $client_id])[$client_id];
    $plugin = $client->getPlugin();
    $scopes = $this->claims->getScopes($plugin);
    $this->session->saveOp('login');
    $response = $plugin->authorize($scopes);

    return $response->send();
  }

}
