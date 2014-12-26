<?php

/**
 * @file
 * Contains Drupal\listener\Tests\DefaultController.
 */

namespace Drupal\listener\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the listener module.
 */
class DefaultControllerTest extends WebTestBase
{


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => "listener DefaultController's controller functionality",
      'description' => 'Test Unit for module listener and controller DefaultController.',
      'group' => 'Other',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests listener functionality.
   */
  public function testDefaultController() {
    // Check that the basic functions of module listener.
    $this->assertEqual(TRUE, TRUE, 'Test Unit Generated via App Console.');
  }
}
