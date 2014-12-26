<?php

/**
 * @file
 * Contains Drupal\listner\Tests\DefaultController.
 */

namespace Drupal\listner\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the listner module.
 */
class DefaultControllerTest extends WebTestBase
{


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => "listner DefaultController's controller functionality",
      'description' => 'Test Unit for module listner and controller DefaultController.',
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
   * Tests listner functionality.
   */
  public function testDefaultController() {
    // Check that the basic functions of module listner.
    $this->assertEqual(TRUE, TRUE, 'Test Unit Generated via App Console.');
  }
}
