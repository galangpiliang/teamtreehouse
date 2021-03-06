<?php

function get_catalog_count($category = null, $search = null){
  $category = strtolower($category);
  $search = strtolower($search);
  include("connection.php");
  try {
    $sql = "SELECT COUNT(media_id) FROM media";
    if(!empty($search)){
      $result = $db->prepare($sql." WHERE LOWER(title) LIKE ?");
      $result->bindValue(1,'%'.$search.'%',PDO::PARAM_STR);
    }
    elseif(!empty($category)){
      $result = $db->prepare($sql." WHERE LOWER(category) = ?");
      $result->bindParam(1,$category,PDO::PARAM_STR);
    }else{
      $result = $db->prepare($sql);
    }
    $result->execute();
  } catch (Exception $e) {
    echo "Bad query";
  }
  $count = $result->fetchColumn(0);
  return $count;
}

function full_catalog_array($limit = null, $offset = 0){
  include('connection.php');
  try {
    $sql = "SELECT media_id, title, category, img FROM Media
          ORDER BY
          REPLACE(
            REPLACE(
              REPLACE(title,'The ',''),
              'An ',''
            ),
            'A ',''
          )";
    if(is_integer($limit)){
      $result = $db->prepare($sql." LIMIT ? OFFSET ?");
      $result->bindParam(1,$limit,PDO::PARAM_INT);
      $result->bindParam(2,$offset,PDO::PARAM_INT);
    } else {
      $result = $db->prepare($sql);
    }
    $result->execute();
  }
  catch(PDOException $e)
  {
    echo "Error: " . $e->getMessage();
  }

  $catalog = $result->fetchAll();
  // var_dump($catalog);
  return $catalog;
}

function category_catalog_array($category, $limit = null, $offset = 0){
  include('connection.php');
  $category = strtolower($category);
  try {
    $sql = "SELECT media_id, title, category, img FROM Media
            WHERE LOWER(category) = ?
            ORDER BY
            REPLACE(
              REPLACE(
                REPLACE(title,'The ',''),
                'An ',''
              ),
              'A ',''
            )";
    if(is_integer($limit)){
      $result = $db->prepare($sql." LIMIT ? OFFSET ?");
      $result->bindParam(1,$category,PDO::PARAM_STR);
      $result->bindParam(2,$limit,PDO::PARAM_INT);
      $result->bindParam(3,$offset,PDO::PARAM_INT);
    } else {
      $result = $db->prepare($sql);
      $result->bindParam(1,$category,PDO::PARAM_STR);
    }
    $result->execute();
  }
  catch(PDOException $e)
  {
    echo "Error: " . $e->getMessage();
  }

  $catalog = $result->fetchAll();
  // var_dump($catalog);
  return $catalog;
}

function search_catalog_array($search, $limit = null, $offset = 0){
  include('connection.php');
  // LIKE operator in SQL query is not case sensitive
  // $search = strtolower($search);
  try {
    $sql = "SELECT media_id, title, category, img FROM Media
            WHERE title LIKE ?
            ORDER BY
            REPLACE(
              REPLACE(
                REPLACE(title,'The ',''),
                'An ',''
              ),
              'A ',''
            )";
    if(is_integer($limit)){
      $result = $db->prepare($sql." LIMIT ? OFFSET ?");
      $result->bindValue(1,'%'.$search.'%',PDO::PARAM_STR);
      $result->bindParam(2,$limit,PDO::PARAM_INT);
      $result->bindParam(3,$offset,PDO::PARAM_INT);
    } else {
      $result = $db->prepare($sql);
      $result->bindValue(1,'%'.$search.'%',PDO::PARAM_STR);
    }
    $result->execute();
  }
  catch(PDOException $e)
  {
    echo "Error: " . $e->getMessage();
  }

  $catalog = $result->fetchAll();
  // var_dump($catalog);
  return $catalog;
}

function random_catalog_array(){
  include('connection.php');
  try {
    $result = $db->query(
          "SELECT media_id, title, category, img FROM Media
          ORDER BY RAND() LIMIT 4");
  }
  catch(PDOException $e)
  {
    echo "Error: " . $e->getMessage();
  }

  $catalog = $result->fetchAll();
  // var_dump($catalog);
  return $catalog;
}

function single_item_array($id){
  include('connection.php');
  try {
    $result = $db->prepare(
          "SELECT title, category, img, format, year, genre, publisher, isbn FROM media
          JOIN genres ON media.genre_id = genres.genre_id
          LEFT OUTER JOIN books ON media.media_id = books.media_id
          WHERE media.media_id = ?");
    $result->bindParam(1,$id,PDO::PARAM_INT);
    $result->execute();
  }
  catch(PDOException $e)
  {
    echo "Error: " . $e->getMessage();
  }

  $item = $result->fetch();
  if(empty($item)) return $item;

  try {
    $result = $db->prepare(
          "SELECT fullname, role FROM media_people
          JOIN people ON media_people.people_id = people.people_id
          WHERE media_people.media_id = ?");
    $result->bindParam(1,$id,PDO::PARAM_INT);
    $result->execute();
  }
  catch(PDOException $e)
  {
    echo "Error: " . $e->getMessage();
  }

  while($row = $result->fetch(PDO::FETCH_ASSOC)){
    $item[$row["role"]][] = $row["fullname"];
  }

  // var_dump($item);
  return $item;
}

function get_item_html($item){
  $output = "<li><a href='details.php?id=".$item["media_id"]."'><img src='".$item["img"]."' alt='".$item["title"]."'><p>View Details</p></a></li>";
  return $output;
}

/*// Dropdown Genre //*/
function get_genre_array($category = null){
  // make query to get group genre and sub genre
  include('connection.php');
  $category = strtolower($category);
  try {
    $sql = "SELECT category, genre FROM genres g
            JOIN genre_categories gc ON g.genre_id = gc.genre_id
            ";
    if($category){
      $result = $db->prepare($sql." WHERE LOWER(category) = ? ORDER BY category, genre");
      $result->bindParam(1,$category,PDO::PARAM_STR);
    } else {
      $result = $db->prepare($sql. " ORDER BY category, genre");
    }
    $result->execute();
  }
  catch(PDOException $e)
  {
    echo "Error: " . $e->getMessage();
  }
  $genres = $result->fetchAll(PDO::FETCH_ASSOC);
  // looping trhough the result
  $output = [];
  foreach($genres as $genre){
      $output[$genre['category']][] = $genre['genre'];
  }
  return $output;
}