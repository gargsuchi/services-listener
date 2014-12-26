<?php

/**
 * @file
 * Contains post.page.inc
 * Post page callback file for the post entity.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for post templates.
 *
 * Default template: post.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */

function template_preprocess_post(&$variables) {
  // Fetch Post Entity Object
  $post = $variables['elements']['#post'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
