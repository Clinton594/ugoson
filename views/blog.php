<!DOCTYPE html>
<html lang="en">

<head>
  <title><?= $generic->name ?>'s Portfolio</title>
  <?php require_once 'includes/links.php'; ?>
</head>

<body class="bg-triangles">

  <main class="main">
    <div class="container gutter-top">
      <div class="row sticky-parent">

        <?php require_once 'includes/sidebar.php'; ?>

        <div class="col-12 col-md-12 col-xl-9">
          <div class="box shadow pb-0">

            <?php require_once 'includes/header.php'; ?>


          </div>

          <footer class="footer">© <?= date("Y") ?> Portfolio</footer>
        </div>
      </div>
    </div>
  </main>

  <?php require_once 'includes/footer.php'; ?>

</body>

</html>