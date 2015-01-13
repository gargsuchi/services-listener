<?php
///**
// * @file
// * Page callbacks for Twitter module.
// */
//
///**
// * Twitter settings form.
// */
//function twitter_admin_form($form, &$form_state) {
//  $form['twitter_import'] = array(
//    '#type' => 'checkbox',
//    '#title' => t('Import and display the Twitter statuses of site users who have entered their Twitter account information.'),
//    '#default_value' => \Drupal::config('twitter.settings')->get('twitter_import'),
//  );
//  $form['twitter_expire'] = array(
//    '#type' => 'select',
//    '#title' => t('Delete old statuses'),
//    '#default_value' => \Drupal::config('twitter.settings')->get('twitter_expire'),
//    '#options' => array(0 => t('Never')) + array_combine(array(
//        604800,
//        2592000,
//        7776000,
//        31536000
//      ), 'format_interval', array(
//        604800,
//        2592000,
//        7776000,
//        31536000
//      )),
//    '#states' => array(
//      'visible' => array(
//        ':input[name=twitter_import]' => array('checked' => TRUE),
//      ),
//    ),
//  );
//
//  $form['oauth'] = array(
//    '#type' => 'fieldset',
//    '#title' => t('OAuth Settings'),
//    '#access' => \Drupal::moduleHandler()->moduleExists('oauth_common'),
//    '#description' => t('To enable OAuth based access for twitter, you must <a href="@url">register your application</a> with Twitter and add the provided keys here.', array('@url' => 'https://dev.twitter.com/apps/new')),
//  );
//
//  $callback_url = Url::fromRoute('twitter.oauth_callback');
//  $form['oauth']['callback_url'] = array(
//    '#type' => 'item',
//    '#title' => t('Callback URL'),
//    '#markup' => $callback_url,
//  );
//
//  $form['oauth']['twitter_consumer_key'] = array(
//    '#type' => 'textfield',
//    '#title' => t('OAuth Consumer key'),
//    '#default_value' => \Drupal::config('twitter.settings')->get('twitter_consumer_key'),
//  );
//  $form['oauth']['twitter_consumer_secret'] = array(
//    '#type' => 'textfield',
//    '#title' => t('OAuth Consumer secret'),
//    '#default_value' => \Drupal::config('twitter.settings')->get('twitter_consumer_secret'),
//  );
//
//  // Twitter external APIs settings.
//  $form['twitter'] = array(
//    '#type' => 'fieldset',
//    '#title' => t('Twitter Settings'),
//    '#description' => t('The following settings connect Twitter module with external APIs. ' .
//      'Change them if, for example, you want to use Identi.ca.'),
//  );
//
//  $form['twitter']['twitter_host'] = array(
//    '#type' => 'textfield',
//    '#title' => t('Twitter host'),
//    '#default_value' => \Drupal::config('twitter.settings')->get('twitter_host'),
//  );
//
//  $form['twitter']['twitter_api'] = array(
//    '#type' => 'textfield',
//    '#title' => t('Twitter API'),
//    '#default_value' => \Drupal::config('twitter.settings')->get('twitter_api'),
//  );
//
//  $form['twitter']['twitter_search'] = array(
//    '#type' => 'textfield',
//    '#title' => t('Twitter search'),
//    '#default_value' => \Drupal::config('twitter.settings')->get('twitter_search'),
//  );
//  $form['twitter']['twitter_tinyurl'] = array(
//    '#type' => 'textfield',
//    '#title' => t('TinyURL'),
//    '#default_value' => \Drupal::config('twitter.settings')->get('twitter_tinyurl'),
//  );
//
//  return system_settings_form($form);
//}
//
///**
// * Form builder that lists Twitter accounts.
// *
// * @param object $account
// *   Optional user account.
// * @return
// *   A list of Twitter accounts and a form to add more.
// */
//function twitter_user_settings($account = NULL) {
//  // Verify OAuth keys.
//  if (!twitter_api_keys()) {
//    $variables = array('@twitter-settings' => Url::fromRoute('twitter.admin_form'));
//    $output = '<p>' . t('You need to authenticate at least one Twitter account in order to use the Twitter API. Please fill out the OAuth fields at <a href="@twitter-settings">Twitter Settings</a> and then return here.', $variables) . '</p>';
//  }
//  else {
//    module_load_include('inc', 'twitter');
//    if (!$account) {
//      $twitter_accounts = twitter_account_load_all();
//    }
//    else {
//      $twitter_accounts = twitter_twitter_accounts($account);
//    }
//
//    $output = array();
//    if (count($twitter_accounts)) {
//      // List Twitter accounts.
//      $output['header']['#markup'] = '<p>';
//      if (\Drupal::currentUser()->hasPermission('administer site configuration')) {
//        $variables = array('@run-cron' => \Drupal\Core\Url::fromRoute('system.run_cron'));
//        $output['header']['#markup'] .= t('Tweets are pulled from Twitter by <a href="@run-cron">running cron</a>.', $variables) . ' ';
//      }
//
//      $variables = array('@tweets' => _url('tweets'));
//
//      $output['header']['#markup'] .= t('You can view the full list of tweets at the <a href="@tweets">Tweets</a> view.', $variables);
//      $output['header']['#markup'] .= '</p>';
//      $output['list_form'] = \Drupal::formBuilder()->getForm('twitter_account_list_form', $twitter_accounts);
//    }
//    else {
//      // No accounts added. Inform about how to add one.
//      $output['header'] = array(
//        '#markup' => '<p>' . t('No Twitter accounts have been added yet. Click on the following button to add one.') . '</p>',
//      );
//    }
//
//    $output['add_account'] = array(
//      '#type' => 'fieldset',
//      '#title' => t('Add Twitter accounts'),
//      '#weight' => 5,
//      '#collapsible' => TRUE,
//      '#collapsed' => FALSE,
//    );
//
//    if (\Drupal::currentUser()->hasPermission('add authenticated twitter accounts')) {
//      $output['add_account']['form'] = \Drupal::formBuilder()->getForm('twitter_auth_account_form');
//    }
//    if (twitter_connect()) {
//      $output['add_account']['non_auth'] = \Drupal::formBuilder()->getForm('twitter_non_auth_account_form');
//    }
//  }
//
//  return $output;
//}
//
///**
// * Formats each Twitter account as a row within a form.
// */
//function twitter_account_list_form($form, $form_state, $twitter_accounts = array()) {
//  $form['#tree'] = TRUE;
//  $form['accounts'] = array();
//
//  foreach ($twitter_accounts as $twitter_account) {
//    $form['accounts'][] = _twitter_account_list_row($twitter_account);
//  }
//
//  if (!empty($twitter_accounts)) {
//    $form['buttons']['submit'] = array(
//      '#type' => 'submit',
//      '#value' => t('Save changes'),
//    );
//  }
//
//  return $form;
//}
//
///**
// * Returns the form fields to manage a Twitter account.
// */
//function _twitter_account_list_row($account) {
//  $form['#account'] = $account;
//
//  $form['id'] = array(
//    '#type' => 'value',
//    '#value' => $account->id,
//  );
//
//  $form['screen_name'] = array(
//    '#type' => 'value',
//    '#value' => $account->screen_name,
//  );
//
//  $form['image'] = array(
//    '#markup' => _theme('image', array('path' => $account->profile_image_url)),
//  );
//
//  $form['visible_name'] = array(
//    '#markup' => _twitter_user_profile($account->screen_name),
//  );
//
//  $form['description'] = array(
//    '#markup' => filter_xss($account->description),
//  );
//
//  if (\Drupal::currentUser()->hasPermission('administer twitter accounts')) {
//    $user = \Drupal::entityManager()->getStorage('user')->load($account->uid);
//
//    $form['user'] = array(
//      '#markup' => l($user->name, _url('user/' . $account->uid)),
//    );
//
//  }
//
//  $form['auth'] = array(
//    '#markup' => $account->is_auth() ? t('Yes') : t('No'),
//  );
//
//  $form['protected'] = array(
//    '#markup' => empty($account->protected) ? t('No') : t('Yes'),
//  );
//
//  $form['import'] = array(
//    '#type' => 'checkbox',
//    '#default_value' => $account->import ? $account->import : '',
//  );
//  if ($account->import == TRUE) {
//    $form['import']['#suffix'] = l('View', _url('tweets/' . $account->screen_name), array('attributes' => array('target' => '_blank')));
//  }
//
//  if ($account->is_auth()) {
//    $form['mentions'] = array(
//      '#type' => 'checkbox',
//      '#default_value' => $account->mentions ? $account->mentions : '',
//    );
//  }
//  else {
//    $form['mentions'] = array(
//      '#markup' => '',
//    );
//  }
//
//  $form['delete'] = array(
//    '#type' => 'checkbox',
//  );
//
//  return $form;
//}
//
///**
// * Themes the list of Twitter accounts.
// */
//function theme_twitter_account_list_form($variables) {
//  $form = $variables['form'];
//
//  $header = array(
//    '',
//    t('Name'),
//    t('Description'),
//  );
//  if (\Drupal::currentUser()->hasPermission('administer twitter accounts')) {
//    $header[] = t('Added by');
//  }
//  $header = array_merge($header, array(
//    t('Auth'),
//    t('Private'),
//    t('Tweets'),
//    t('Mentions'),
//    t('Delete'),
//  ));
//
//  $rows = array();
//  foreach (\Drupal\Core\Render\Element::children($form['accounts']) as $key) {
//    $element = & $form['accounts'][$key];
//    $row = array(
//      drupal_render($element['image']),
//      drupal_render($element['id']) . drupal_render($element['screen_name']) .
//      drupal_render($element['visible_name']),
//      drupal_render($element['description']),
//    );
//    if (\Drupal::currentUser()->hasPermission('administer twitter accounts')) {
//      $row[] = drupal_render($element['user']);
//    }
//    $row = array_merge($row, array(
//      drupal_render($element['auth']),
//      drupal_render($element['protected']),
//      drupal_render($element['import']),
//      drupal_render($element['mentions']),
//      drupal_render($element['delete']),
//    ));
//    $rows[] = $row;
//  }
//
//  $output = _theme('table', array('header' => $header, 'rows' => $rows));
//  $output .= drupal_render_children($form);
//  return $output;
//}
//
///**
// * Form submit handler for altering the list of Twitter accounts.
// */
//function twitter_account_list_form_submit($form, &$form_state) {
//  $accounts = $form_state['values']['accounts'];
//  foreach ($accounts as $account) {
//    if (empty($account['delete'])) {
//      twitter_account_save($account);
//    }
//    else {
//      $twitter_account = twitter_account_load($account['id']);
//      twitter_account_delete($account['id']);
//      drupal_set_message(t('The Twitter account <em>!account</em> was deleted.',
//        array('!account' => $twitter_account->screen_name)));
//    }
//  }
//  drupal_set_message(t('The Twitter account settings were updated.'));
//}
//
///**
// * Form to add an authenticated Twitter account.
// */
//function twitter_auth_account_form($form, $form_state) {
//  $form['submit'] = array(
//    '#type' => 'submit',
//    '#value' => t('Go to Twitter to add an authenticated account'),
//    '#prefix' => t('Authenticated accounts can post, sign in and pull mentions. ' .
//      'At least one authenticated account is needed for Twitter ' .
//      'module to work.</br>'),
//  );
//
//  return $form;
//}
//
///**
// * Form validation for adding a new Twitter account.
// */
//function twitter_auth_account_form_validate($form, &$form_state) {
//  $key = \Drupal::config('twitter.settings')->get('twitter_consumer_key');
//  $secret = \Drupal::config('twitter.settings')->get('twitter_consumer_secret');
//  if ($key == '' || $secret == '') {
//    $url_admin = Url::fromRoute('twitter.admin_form');
//    form_set_error('', t('Please configure your consumer key and secret key at ' .
//      '<a href="!url">Twitter settings</a>.', array(
//        '!url' => $url_admin,
//      )));
//  }
//}
//
///**
// * Form submit handler for adding a Twitter account.
// *
// * Loads Twitter account details and adds them to the user account
// */
//function twitter_auth_account_form_submit($form, &$form_state) {
//  $key = \Drupal::config('twitter.settings')->get('twitter_consumer_key');
//  $secret = \Drupal::config('twitter.settings')->get('twitter_consumer_secret');
//  $twitter = new Twitter($key, $secret);
//  $token = $twitter->get_request_token();
//  if ($token) {
//    $_SESSION['twitter_oauth']['token'] = $token;
//    $_SESSION['twitter_oauth']['destination'] = $_GET['q'];
//    // Check for the overlay.
//    if (\Drupal::moduleHandler()->moduleExists('overlay') && overlay_get_mode() == 'child') {
//      overlay_close_dialog($twitter->get_authorize_url($token), array('external' => TRUE));
//      overlay_deliver_empty_page();
//    }
//    else {
//      drupal_goto($twitter->get_authorize_url($token));
//    }
//  }
//  else {
//    drupal_set_message(t('Could not obtain a valid token from the Twitter API. Please review the configuration.'),
//      'error');
//  }
//}
//
///**
// * Wrapper to call drupal_form_submit() which wasn't required in D6.
// */
//function twitter_oauth_callback() {
//  if (isset($_GET['denied']) || empty($_GET['oauth_token'])) {
//    drupal_set_message(t('The connection to Twitter failed. Please try again.'), 'error');
//    $user = \Drupal::currentUser();
//    if ($user->uid) {
//      // User is logged in, was attempting to OAuth a Twitter account.
//      drupal_goto('admin/config/services/twitter');
//    }
//    else {
//      // Anonymous user, redirect to front page.
//      drupal_goto('<front>');
//    }
//  }
//  $form_state['values']['oauth_token'] = $_GET['oauth_token'];
//  \Drupal::formBuilder()->submitForm('twitter_oauth_callback_form', $form_state);
//}
//
///**
// * Form builder function. In D6 this form was built in response to the
// * oauth return request from Twitter, and the setting of
// * $form['#post'] seems to have caused the form to be validated and
// * processed.
// */
//function twitter_oauth_callback_form($form, &$form_state) {
//  $form['#post']['oauth_token'] = $_GET['oauth_token'];
//  $form['oauth_token'] = array(
//    '#type' => 'hidden',
//    '#default_value' => $_GET['oauth_token'],
//  );
//  return $form;
//}
//
///**
// * Validate results from Twitter OAuth return request.
// */
//function twitter_oauth_callback_form_validate($form, &$form_state) {
//  $key = \Drupal::config('twitter.settings')->get('twitter_consumer_key');
//  $secret = \Drupal::config('twitter.settings')->get('twitter_consumer_secret');
//
//  if (isset($_SESSION['twitter_oauth'])) {
//    $form_state['twitter_oauth'] = $_SESSION['twitter_oauth'];
//    unset($_SESSION['twitter_oauth']);
//  }
//  else {
//    form_set_error('oauth_token', 'Invalid Twitter OAuth request');
//  }
//
//  if (isset($form_state['twitter_oauth']['token'])) {
//    $token = $form_state['twitter_oauth']['token'];
//    if (!is_array($token) || !$key || !$secret) {
//      form_set_error('oauth_token', t('Invalid Twitter OAuth request'));
//    }
//    if ($token['oauth_token'] != $form_state['values']['oauth_token']) {
//      form_set_error('oauth_token', t('Invalid OAuth token.'));
//    }
//  }
//  else {
//    form_set_error('oauth_token', t('Invalid Twitter OAuth request'));
//  }
//
//  module_load_include('inc', 'twitter');
//
//  if ($twitter = new Twitter($key, $secret, $token['oauth_token'], $token['oauth_token_secret'])) {
//    //Collect oauth_verifier from url
//    if ($response = $twitter->get_access_token($_GET['oauth_verifier'])) {
//      $form_state['twitter_oauth']['response'] = $response;
//    }
//    else {
//      form_set_error('oauth_token', t('Invalid Twitter OAuth request'));
//    }
//  }
//  else {
//    form_set_error('oauth_token', t('Invalid Twitter OAuth request'));
//  }
//}
//
///**
// * Handle a Twitter OAuth return request and store the account creds
// * in the DB.
// */
//function twitter_oauth_callback_form_submit($form, &$form_state) {
//  $key = \Drupal::config('twitter.settings')->get('twitter_consumer_key');
//  $secret = \Drupal::config('twitter.settings')->get('twitter_consumer_secret');
//  $response = $form_state['twitter_oauth']['response'];
//
//  $twitter = new Twitter($key, $secret, $response['oauth_token'], $response['oauth_token_secret']);
//  try {
//    $twitter_account = $twitter->users_show($response['screen_name']);
//  }
//  catch (TwitterException $e) {
//    form_set_error('screen_name', t('Request failed: @message.', array('@message' => $e->getMessage())));
//    return;
//  }
//  // Save the new Twitter account and set the user's uid who added it.
//  $twitter_account->set_auth($response);
//  $user = \Drupal::currentUser();
//  $twitter_account->uid = $user->uid;
//  twitter_account_save($twitter_account, TRUE);
//
//  $form_state['programmed'] = FALSE;
//  $form_state['redirect'] = $form_state['twitter_oauth']['destination'];
//}
//
///**
// * Form to add a non-authenticated Twitter account.
// */
//function twitter_non_auth_account_form($form, $form_state) {
//  $form['screen_name'] = array(
//    '#type' => 'textfield',
//    '#required' => TRUE,
//    '#title' => t('Twitter account name'),
//    '#prefix' => t('If you simply want to pull tweets from additional Twitter accounts, ' .
//      'enter the Twitter account name below and click on the following button.</br>'),
//  );
//
//  $form['submit_non_auth'] = array(
//    '#type' => 'submit',
//    '#value' => t('Add a non-authenticated account'),
//  );
//
//  return $form;
//}
//
///**
// * Form validation for adding a new non-authenticated Twitter account.
// */
//function twitter_non_auth_account_form_validate($form, &$form_state) {
//  $screen_name = $form_state['values']['screen_name'];
//  if (twitter_account_load($screen_name)) {
//    form_set_error('screen_name', t('The Twitter account <em>@screen_name</em> has been added already.',
//      array('@screen_name' => $screen_name)));
//  }
//}
//
///**
// * Submit form handler to add a non-authenticated Twitter account.
// */
//function twitter_non_auth_account_form_submit($form, &$form_state) {
//  $name = $form_state['values']['screen_name'];
//  $twitter = twitter_connect();
//  $twitter_account = $twitter->users_show($name, FALSE);
//  if (!isset($twitter_account->id)) {
//    form_set_error('screen_name', t('Could not add the Twitter account <em>@name</em>. ' .
//      'Check the recent messages log.', array('@name' => $name)));
//  }
//  else {
//    $user = \Drupal::currentUser();
//    $twitter_account->uid = $user->uid;
//    twitter_account_save($twitter_account, FALSE);
//    drupal_set_message(t('Twitter account added successfully'));
//  }
//}