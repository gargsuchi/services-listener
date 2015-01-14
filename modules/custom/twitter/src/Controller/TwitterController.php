<?php /**
 * @file
 * Contains \Drupal\twitter\Controller\TwitterController.
 */

namespace Drupal\twitter\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\UserInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Default controller for the twitter module.
 */
class TwitterController extends ControllerBase {

  /**
   * The account the twitter account is for.
   *
   * @var \Drupal\user\UserInterface
   */
 // protected $user;

  /**
   * Provide a single block from the administration menu as a page.
   */
  public function admin() {
    $item = menu_get_item();
    if ($content = system_admin_menu_block($item)) {
      $output = theme('admin_block_content', array('content' => $content));
    }
    else {
      $output = t('You do not have any administrative items.');
    }
    return $output;
  }

  public function twitter_user_settings(UserInterface $user) {

    if (!$user) {
      $user = \Drupal::currentUser();
    }
    // Verify OAuth keys.
    if (!twitter_api_keys()) {
      $settings_url = \Drupal::URL('admin/config/services/twitter/settings');
      $variables = array('@twitter-settings' => $settings_url);
      $output = '<p>' . t('You need to authenticate at least one Twitter account in order to use the Twitter API. Please fill out the OAuth fields at <a href="@twitter-settings">Twitter Settings</a> and then return here.', $variables) . '</p>';
    }
    else {
      module_load_include('inc', 'twitter');
      if (!$user) {
        $twitter_accounts = twitter_account_load_all();
      }
      else {
        $twitter_accounts = twitter_twitter_accounts($user);
      }



      $output = [];
      if (count($twitter_accounts)) {
        // List Twitter accounts.
        $output['header']['#markup'] = '<p>';
        if (\Drupal::currentUser()->hasPermission('administer site configuration')) {
          $variables = [
            '@run-cron' => \Drupal\Core\Url::fromRoute('system.run_cron')
          ];
          $output['header']['#markup'] .= t('Tweets are pulled from Twitter by <a href="@run-cron">running cron</a>.', $variables) . ' ';
        }

        $variables = array('@tweets' => _url('tweets'));

        $output['header']['#markup'] .= t('You can view the full list of tweets at the <a href="@tweets">Tweets</a> view.', $variables);
        $output['header']['#markup'] .= '</p>';
        $output['list_form'] = \Drupal::formBuilder()->getForm('twitter_account_list_form', $twitter_accounts);
      }
      else {
        // No accounts added. Inform about how to add one.

        $output['header'] = [
          '#markup' => '<p>' . t('No Twitter accounts have been added yet. Click on the following button to add one.') . '</p>'
          ];
      }

      $output['add_account'] = [
        '#type' => 'fieldset',
        '#title' => t('Add Twitter accounts'),
        '#weight' => 5,
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
      ];


      if (\Drupal::currentUser()->hasPermission('add authenticated twitter accounts')) {
     //   $output['add_account']['form'] = \Drupal::formBuilder()->getForm('Drupal\twitter\Form\TwitterAuthAccountForm');
      }
      if (twitter_connect()) {
        //$output['add_account']['non_auth'] = \Drupal::formBuilder()->getForm('twitter_non_auth_account_form');
      }
    }
    $output = \Drupal::formBuilder()->getForm('Drupal\twitter\Form\TwitterAuthAccountForm');
    //echo "<pre>"; print_r($output);
    echo render($output); //die;
    $build['#markup'] = render($output);

    return $build;

  }

  public function twitter_account_access() {
    $user = \Drupal::currentUser();

    if ($user->hasPermission('add twitter accounts') || $user->hasPermission('add authenticated twitter accounts')) {
      // Administrators can switch anyone's shortcut set.
      return AccessResult::allowed()->cachePerRole();
    }
    return FALSE;
  }

}
