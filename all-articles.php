<?php
  require("includes/config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $config["title"]?></title>

  <!-- Bootstrap Grid -->
  <link rel="stylesheet" type="text/css" href="/media/assets/bootstrap-grid-only/css/grid12.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

  <!-- Custom -->
  <link rel="stylesheet" type="text/css" href="/media/css/style.css">
</head>
<body>

  <div id="wrapper">

    <?php include("includes/header.php");?>

    <div id="content">
      <div class="container">
        <div class="row">
          <section class="content__left col-md-8">
            <div class="block">
              <h3>All in blog</h3>
              <div class="block__content">
                <div class="articles articles__horizontal">

                  <?php
                    $per_page = 4;
                    $page = 1;

                    if ( isset($_GET['page'])){
                      $page = (int) $_GET['page'];
                    };
                    $total_count_q = mysqli_query($connection, "SELECT COUNT(`id`) as `total_count` FROM `articles` ORDER BY `id`");
                    $total_count = mysqli_fetch_assoc($total_count_q);
                    $total_count = $total_count['total_count'];
                    $total_pages = ceil($total_count / $per_page);
                    if ($page <= 1 || $page > $total_pages) {
                      $page = 1;
                    };
                    
                    $offset = ($per_page * $page) - $per_page;
                    
                    $articles = mysqli_query($connection, "SELECT * FROM `articles` ORDER BY `id` DESC LIMIT $offset,$per_page");
                    $articles_exist = true;
                    if( mysqli_num_rows($articles) <= 0){
                      echo "No articles";
                      $articles_exist= false;
                    };

                    if( isset($_GET['category']) ){
                      $articles = mysqli_query($connection, "SELECT * FROM `articles` WHERE `category_id` =" . $_GET['category']);
                    }

                    while( $art = mysqli_fetch_assoc($articles) ){
                      ?>
                        <article class="article">
                          <div class="article__image" style="background-image: url(/static/images/<?php echo $art['img'];?>);"></div>
                          <div class="article__info">
                            <a href="/articles.php?id=<?php echo $art['id'];?>"><?php echo $art['title'];?></a>
                            <div class="article__info__meta">
                              <?php
                                foreach( $categories as $cat){
                                  if($cat['id'] == $art['category_id']){
                                    ?>
                                      <small>Category: <a href="/articles.php?category=<?php echo $art['category_id']?>"><?php echo $cat['title']?></a></small>
                                    <?php
                                  };
                                };
                              ?>
                            </div>
                            <div class="article__info__preview"><?php echo mb_substr($art['text'], 0, 100, 'utf-8') . '...'; ?></div>
                          </div>
                        </article>
                      <?php
                    };
                  ?> 
                </div>
                <?php
                  if( $articles_exist == true){
                    echo '<div class="paginator">';
                    if($page > 1){
                      echo '<a href="/all-articles.php?page='.($page - 1).'">Prev page  </a>';
                    }
                    if($page < $total_pages){
                      echo '<a href="/all-articles.php?page='.($page + 1).'"> Next page</a>';
                    }
                    echo '</div>';
                  }
                ?>
              </div>
            </div>
          </section>
          <section class="content__right col-md-4">
            <?php include("includes/side-bar.php");?>
          </section>
        </div>
      </div>
    </div>

    <?php include("includes/footer.php");?>

  </div>

</body>
</html>