<?php
// New excerpt
function stringLimitWords($string, $word_limit) {
  $words = explode(' ', $string, ($word_limit + 1));
  if (count($words) > $word_limit) {
    array_pop($words);
    echo implode(' ', $words) . "... ";
    echo '<br><a href="' . get_permalink($post->ID) . '">  See more... ></a>';
  } else {
    echo implode(' ', $words);
  }
}
// adv count paragraph if is greater than 15 add adv after 2 paragraph
function advAfterSecondParagraph($content) {
  if (!is_single()) {
    return $content;
  }
  $para_count = substr_count($content, "</p>");
  $para_After = floor($para_count);
  $para_After = 2;
  $content = explode("</p>", $content);
  $new_content = '';
  if ($para_count > 15) {
    for ($i = 0;$i < count($content);$i++) {
      if ($i == $para_After) {
        $new_content.= '
        <div id="adv1">
        </div>
        <!-- adv1 --> ';
      }
      $new_content.= $content[$i] . "</p>";
    }
    return $new_content;
  } else {
    for ($i = 0;$i < count($content);$i++) {
      $new_content.= $content[$i] . "</p>";
    }
    return $new_content;
  }
}
// adv middle of content
function advMidContent($content) {
  if (!is_single()) {
    return $content;
  }
  $para_count = substr_count($content, "</p>");
  $para_After = floor($para_count / 2);
  $content = explode("</p>", $content);
  $new_content = '';
  for ($i = 0;$i < count($content);$i++) {
    if ($i == $para_After) {
      $new_content.= '';
      $new_content.= '
      <div id="adv2">
      </div>
      <!-- adv2 --> ';
      $new_content.= '</div>';
    }
    $new_content.= $content[$i] . "</p>";
  }
  return $new_content;
}
/* my sitemap.xml  */
function createSitemap() {
  if (str_replace('-', '', get_option('gmt_offset')) < 10) {
    $tempo = '+0' . str_replace('-', '', get_option('gmt_offset'));
  } else {
    $tempo = get_option('gmt_offset');
  }
  if (strlen($tempo) == 3) {
    $tempo = $tempo . ':00';
  }
  $postsForSitemap = get_posts(array('numberposts' => 200, 'orderby' => 'modified', 'post_type' => array('post'), 'category_name' => 'ygeia', 'order' => 'DESC'));
  $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
  $sitemap.= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">' . "\n";
  $sitemap.= "\t" . '<url>' . "\n" . "\t\t" . '<loc>' . esc_url(home_url('/')) . '</loc>' . "\n\t\t" . '<lastmod>' . date('Y-m-d') . 'T' . date("H:i:s") . '+00:00' . '</lastmod>' . "\n\t" . '</url>' . "\n";
  foreach ($postsForSitemap as $post) {
    setup_postdata($post);
    $postdate = explode(" ", $post->post_modified);
    $sitemap.= "\t" . '<url>' . "\n" . "\t\t" . '<loc>' . get_permalink($post->ID) . '</loc>' . "\n\t\t" . '<lastmod>' . $postdate[0] . 'T' . $postdate[1] . '+00:00' . '</lastmod>' . "\n\t" . '</url>' . "\n";
  }
  $sitemap.= '</urlset>';
  $fp = fopen(ABSPATH . "sitemap.xml", 'w');
  fwrite($fp, $sitemap);
  fclose($fp);
}
// remove empty p
function removeEmptyParagraph($content) {
  $content = force_balance_tags($content);
  return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
}
// count views and get views
function sPostViews($postID) {
  $count_key = 'post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if ($count == '') {
    $count = 0;
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, '0');
  } else {
    $count++;
    update_post_meta($postID, $count_key, $count);
  }
}
function gPostViews($postID) {
  $count_key = 'post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if ($count == '') {
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, '0');
    return "0 View";
  }
  return $count . ' ';
}
// short title
function shortTitle($after = '', $length) {
  $mytitle = explode(' ', get_the_title(), $length);
  if (count($mytitle) >= $length) {
    array_pop($mytitle);
    $mytitle = implode(" ", $mytitle) . $after;
  } else {
    $mytitle = implode(" ", $mytitle);
  }
  return $mytitle;
}
