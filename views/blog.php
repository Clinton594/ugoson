<!DOCTYPE html>
<html lang="en">
<head>
  <title><?=$generic->name?>'s Portfolio</title>
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

            <div class="pb-2">
              <h1 class="title title--h1 title__separate">Portfolio</h1>
            </div>

            <div class="news-grid pb-0">
              <article class="news-item box">
                <div class="news-item__image-wrap overlay overlay--45">
                  <div class="news-item__date">E-Commerce</div>
                  <a class="news-item__link" href="https://ahiamoni.com"></a>
                  <img class="cover lazyload" src="<?=$uri->site?>assets/images/ahiamoni.png" alt="" />
                </div>
                <div class="news-item__caption">
                  <h2 class="title title--h4">Ahiamoni</h2>
                  <p>Online Stores, Product Listing and Adverts</p>
                </div>
              </article>

              <article class="news-item box">
                <div class="news-item__image-wrap overlay overlay--45">
                  <div class="news-item__date">Finance</div>
                  <a class="news-item__link" href="https://apexforexglobal.com/"></a>
                  <img class="cover lazyload" src="<?=$uri->site?>assets/images/apex.png" alt="" />
                </div>
                <div class="news-item__caption">
                  <h2 class="title title--h4">Apex Forex Global</h2>
                  <p>Forex and Crypto Investments Website.</p>
                </div>
              </article>

              <article class="news-item box">
                <div class="news-item__image-wrap overlay overlay--45">
                  <div class="news-item__date">Transport & Logistics</div>
                  <a class="news-item__link" href="https://skylinkng.com/"></a>
                  <img class="cover lazyload" src="<?=$uri->site?>assets/images/booking.png" alt="" />
                </div>
                <div class="news-item__caption">
                  <h2 class="title title--h4">Skylink Transport & Logistics</h2>
                  <p>Enug Based Transport and Logistics Company with Routes (Lagos, Abuja, Owerri)</p>
                </div>
              </article>


              <article class="news-item box">
                <div class="news-item__image-wrap overlay overlay--45">
                  <div class="news-item__date">Company</div>
                  <a class="news-item__link" href="https://digitaldreamsng.com/"></a>
                  <img class="cover lazyload" src="<?=$uri->site?>assets/images/digitaldreams.png" alt="" />
                </div>
                <div class="news-item__caption">
                  <h2 class="title title--h4">Digital Dreams</h2>
                  <p>One of the leading IT firms in Nigeria registered by corporate affairs commission of Nigeria in September 2007 with registration No. RC 708352.</p>
                </div>
              </article>

              <article class="news-item box">
                <div class="news-item__image-wrap overlay overlay--45">
                  <div class="news-item__date">Real Estates</div>
                  <a class="news-item__link" href="https://geohomesgroup.com/"></a>
                  <img class="cover lazyload" src="<?=$uri->site?>assets/images/geohomes.png" alt="" />
                </div>
                <div class="news-item__caption">
                  <h2 class="title title--h4">GeoHomes Group</h2>
                  <p>Leading Real Estate Company In Nigeria known for establishing Real Estate Solutions.</p>
                </div>
              </article>
              <article class="news-item box">
                <div class="news-item__image-wrap overlay overlay--45">
                  <div class="news-item__date">Finance</div>
                  <a class="news-item__link" href="https://dgitpay.com/"></a>
                  <img class="cover lazyload" src="<?=$uri->site?>assets/images/dgitpay.png" alt="" />
                </div>
                <div class="news-item__caption">
                  <h2 class="title title--h4">Dgit Pay</h2>
                  <p>Get Paid trading, buying and Selling Ownership of your valueable assets.</p>
                </div>
              </article>
            </div>
          </div>

          <footer class="footer">© <?=date("Y")?> Portfolio</footer>
        </div>
      </div>
    </div>
  </main>

  <?php require_once 'includes/footer.php'; ?>

</body>
</html>
