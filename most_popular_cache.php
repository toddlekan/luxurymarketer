<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//$ip = $_SERVER['REMOTE_ADDR'];

//if($ip != '107.21.48.91'){
//print "none";
//die();
//}
$url_root = 'https://www.luxuryroundtable.com/wp-content/themes/LuxuryRoundtable_2023';
$conn = mysqli_connect(
  'luxurydaily-aurora-cluster.cluster-cvjnrujklarl.us-east-1.rds.amazonaws.com',
  'luxurydaily',
  'thaTe2AG',
  'luxury_roundtable'
);

// $conn = mysqli_connect(
//   'mysql.luxuryroundtable.ouranostech.com',
//   'luxuryroundtable',
//   'UCysNTWs',
//   'luxury_roundtable'
// );

echo '\r\n\r\n*****************\r\n\r\n';

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}



mysqli_query($conn, "set character_set_client='utf8'");
mysqli_query($conn, "set character_set_results='utf8'");

mysqli_query($conn, "set collation_connection='utf8_general_ci'");

$sql = "SELECT p.*
FROM wp_most_popular mp
INNER JOIN wp_posts p ON mp.post_id = p.ID
WHERE
p.post_type = 'post' AND
p.post_status = 'publish' AND
p.post_title NOT LIKE '%Van Cleef%'
ORDER BY 1_day_stats DESC
LIMIT 20
;";

$result = mysqli_query($conn, $sql);

$content_array = [];
$content = "";
$content_2016 = "";
$content_2016_count = 0;

$count = 0;

$carousel_content = "";

$carousel_count = 0;

$carousel_categories = array();

print "1\r\n";
while ($row = mysqli_fetch_array($result)) {

  print "2\r\n";

  $post_name = $row['post_name'];
  $post_title = $row['post_title'];
  $post_content = $row['post_content'];
  $ID = $row['ID'];

  $unlocked_sql = "
    SELECT meta_value
    FROM wp_postmeta
    WHERE meta_key = 'unlocked' and post_id = $ID;
       ";

  $unlocked_result = mysqli_query($conn, $unlocked_sql);

  $unlocked = '';

  while ($unlocked_row = mysqli_fetch_array($unlocked_result)) {

    print "3\r\n";
    $unlocked = $unlocked_row['meta_value'];
    break;
  }

  if ($carousel_count >= 4 && $content_2016_count >= 10) {

    break;
  } else {

    if ($carousel_count < 4) {

      $pattern = '/\[caption(.*?)\[\/caption\]/';

      preg_match_all($pattern, $post_content, $matches);

      if (count($matches)) {

        $match = $matches[0];

        if (count($match)) {

          $caption = $match[0];

          if ($caption) {

            $start = strpos($caption, '<a ');

            $end = strpos($caption, '</a>');

            $thumbnail = substr($caption, $start, $end - $start + 4);

            $category_sql = "
              SELECT wp_term_taxonomy.term_id FROM wp_term_relationships, wp_term_taxonomy
              WHERE wp_term_relationships.object_id = $ID
              AND wp_term_taxonomy.taxonomy = 'category'
              AND wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
            ";

            $category_result = mysqli_query($conn, $category_sql);

            while ($category_row = mysqli_fetch_array($category_result)) {

              $category_id = $category_row['term_id'];
            }

            if ($category_id && !in_array($category_id, $carousel_categories)) {

              $carousel_categories[] = $category_id;

              $category_sql = "
                SELECT wp_terms.name
                FROM wp_terms
                WHERE term_id = $category_id;
                   ";

              $category_result = mysqli_query($conn, $category_sql);

              while ($category_row = mysqli_fetch_array($category_result)) {

                $category_name = $category_row['name'];
              }

              $carousel_content .= '
                <li>
                  <div class="redcategory">
                    <a href="https://www.luxurydaily.com/?cat=' . $category_id . '">' . $category_name . '</a>
                  </div>
                  <div>
                    ' . $thumbnail . '
                  </div>
                  <div class="headline">
                    <a class="title="' . $post_title . '" rel="bookmark" href="https://www.luxurydaily.com/' . $post_name . '">
                      ' . $post_title . '
                    </a>&nbsp;' . ($unlocked ? '<img src="' . $url_root . '/img/ic_unlock.png"/>' : '')  . '
                  </div>
                </li>
              ';

              $carousel_count++;
            }
          }
        }
      }
    }
  }


  if ($count < 5) {


    if ($post_name && $post_title) {
      $content .= '<li><a href="https://www.luxuryroundtable.com/' . 
        $post_name . 
        '" title="' . 
        $post_title . '">' . 
        $post_title . 
        ($unlocked ? '&nbsp;<img src="' . $url_root . '/img/ic_unlock.png"/>' : '') .
        '</a>' . 
        '</li>';

      //print "adding $post_name\r\n";
    }
  }

  if ($content_2016_count < 10) {

    if ($post_name && $post_title) {
      
      $content_2016 .= '<li><a href="https://www.luxuryroundtable.com/' . 
        $post_name . 
        '" title="' . $post_title . 
        '">' . $post_title . 
        ($unlocked ? '&nbsp;<img src="' . $url_root . '/img/ic_unlock.png"/>' : '')  . 
        '</a>' .
        '</li>';

      $content_2016_count++;
      $post["ID"] = $ID;
      $post["post_name"] = $post_name;
      $post["post_title"] = $post_title;
      array_push($content_array, $post);
      //print 'count ' . $content_2016_count;

      //print "adding $post_name\r\n";
    }
  }

  $count++;
}

print $content_2016;

$base = "/tmp";
$cache_file_json = "$base/most_popular_json.lr.cache";
$cache_file = "$base/most_popular.lr.cache";
$cache_file_2016 = "$base/most_popular_2016.lr.cache";
$carousel_cache_file = "$base/most_popular_carousel.lr.cache";

function update($cache_file, $content)
{
  unlink($cache_file);
  $working = json_encode($content);
  $working = preg_replace('/\\\u([0-9a-z]{4})/', '&#x$1;', $working);
  $content = json_decode($working);
  file_put_contents($cache_file, $content);
}

function updateMobileJsonFile($cache_file, $content)
{
  unlink($cache_file);
  file_put_contents($cache_file, json_encode($content));
}

updateMobileJsonFile($cache_file_json, $content_array);
update($cache_file, $content);
update($cache_file_2016, $content_2016);
update($carousel_cache_file, $carousel_content);
