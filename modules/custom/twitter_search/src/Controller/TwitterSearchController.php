<?php

/**
 * @file
 * Contains \Drupal\twitter_search\Controller\TwitterSearchController.
 */

namespace Drupal\twitter_search\Controller;

use Drupal\Core\Url;

/**
 * Default controller for the twitter_search module.
 */
class TwitterSearchController {

  public function twitter_search_list_page() {
    // Get search strings from database.
    $sql = "SELECT twitter_search_id, search
          FROM {twitter_search}
          ORDER BY last_refresh";

    $results = db_query($sql);
    $header = [t('Search'), t('Operations')];

    $url = Url::fromRoute('twitter_search.add_form');
    $add_url = \Drupal::l(t('+ Add a new Search term'), $url);
    $add_html = "<div class='add_search'>" . $add_url . "</div>";

    $rows = [];

    while ($search = $results->fetchAssoc()) {

      $del_url = _url('admin/config/services/twitter/twitter_search/delete/' . $search['twitter_search_id'], array('absolute' => true));

      $url = Url::fromUri($del_url);
      $del_url = \Drupal::l(t('delete'), $url);


      $rows[] = array(
        ($search['search']),
        array('data' => $del_url),
      );

    }
    if (empty($rows)) {
      $rows[] = [
        [
          'data' => t('No search terms have been created.'),
          'colspan' => '2',
          'class' => 'message',
        ]
      ];
    }

    $table = array( '#type' => 'table', '#header' => $header, '#rows' => $rows, '#attributes' => array( 'id' => 'twitter-search-table', ), );

    $build['#markup'] = drupal_render($table) . $add_html;

    return $build;
  }

}
