<?php

/**
 * @file
 * Contains \Drupal\Core\Cache\ApcuBackendFactory.
 */

namespace Drupal\Core\Cache;

use \Drupal\Component\Utility\Crypt;

class ApcuBackendFactory implements CacheFactoryInterface {

  /**
   * The site prefix string.
   *
   * @var string
   */
  protected $sitePrefix;

  /**
   * Constructs an ApcuBackendFactory object.
   *
   * @param string $root
   *   The app root.
   */
  public function __construct($root) {
    $this->sitePrefix = Crypt::hashBase64($root . '/' . conf_path());
  }

  /**
   * Gets ApcuBackend for the specified cache bin.
   *
   * @param $bin
   *   The cache bin for which the object is created.
   *
   * @return \Drupal\Core\Cache\ApcuBackend
   *   The cache backend object for the specified cache bin.
   */
  public function get($bin) {
    return new ApcuBackend($bin, $this->sitePrefix);
  }

}
