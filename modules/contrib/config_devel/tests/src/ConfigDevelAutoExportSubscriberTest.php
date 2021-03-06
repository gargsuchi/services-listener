<?php

/**
 * @file
 * Contains \Drupal\config_devel\Tests\ConfigDevelAutoExportSubscriberTest.
 */

namespace Drupal\config_devel\Tests;

use org\bovigo\vfs\vfsStream;
use Drupal\Component\Serialization\Yaml;

use Drupal\config_devel\EventSubscriber\ConfigDevelAutoExportSubscriber;

/**
 * @coversDefaultClass \Drupal\config_devel\EventSubscriber\ConfigDevelAutoExportSubscriber
 * @group config_devel
 */
class ConfigDevelAutoExportSubscriberTest extends ConfigDevelTestBase {

  /**
   * Test ConfigDevelAutoExportSubscriber::writeBackConfig().
   */
  public function testWriteBackConfig() {
    $config_data = array(
      'id' => $this->randomName(),
      'langcode' => 'en',
      'uuid' => '836769f4-6791-402d-9046-cc06e20be87f',
    );

    $config = $this->getMockBuilder('\Drupal\Core\Config\Config')
      ->disableOriginalConstructor()
      ->getMock();
    $config->expects($this->any())
      ->method('getName')
      ->will($this->returnValue($this->randomName()));
    $config->expects($this->any())
      ->method('get')
      ->will($this->returnValue($config_data));

    $file_names = array(
      vfsStream::url('public://' . $this->randomName() . '.yml'),
      vfsStream::url('public://' . $this->randomName() . '.yml'),
    );

    $configDevelSubscriber = new ConfigDevelAutoExportSubscriber($this->configFactory, $this->configManager, $this->fileStorage);
    $configDevelSubscriber->writeBackConfig($config, $file_names);

    $data = $config_data;
    unset($data['uuid']);

    foreach ($file_names as $file_name) {
      $this->assertEquals($data, Yaml::decode(file_get_contents($file_name)));
    }
  }

}
