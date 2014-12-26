<?php

/**
 * @file
 * Contains Drupal\listener\Tests\Listener.
 */

namespace Drupal\listener\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the listener module.
 */
class ListenerTest extends WebTestBase
{


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => "listener Listener's controller functionality",
      'description' => 'Test Unit for module listener and controller Listener.',
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
  public function testListener() {
    // Check that the basic functions of module listener.
    $this->assertEqual(TRUE, TRUE, 'Test Unit Generated via App Console.');
  }
}
