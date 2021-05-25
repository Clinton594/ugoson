<!DOCTYPE html>
<html lang="en">
<head>
  <title>About <?=$generic->name?></title>
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

            <div class="pb-0 pb-sm-2">
              <h1 class="title title--h1 title__separate">About Me</h1>
              <p>
                <?=$generic->description?>
              </p>
            </div>

            <div class="box-inner pb-0">
              <h2 class="title title--h3">What I'm Doing</h2>
              <div class="row">
                <div class="col-12 col-lg-6">
                  <div class="case-item box box__second">
                    <img class="case-item__icon" src="<?=$uri->site?>assets/icons/browser-web-development-svgrepo-com.svg" alt="" />
                    <div>
                      <h3 class="title title--h5">BackEnd Web Development</h3>
                      <p class="case-item__caption">High-quality and professional development of websites at the professional level.</p>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-lg-6">
                  <div class="case-item box box__second">
                    <img class="case-item__icon" src="<?=$uri->site?>assets/icons/web-design-svgrepo-com.svg" alt="" />
                    <div>
                      <h3 class="title title--h5">FrontEnd Web Development</h3>
                      <p class="case-item__caption">I am not fully into UI/UX but i design very good user friendly interfaces.</p>
                    </div>
                  </div>
                </div>


                <div class="col-12 col-lg-6">
                  <div class="case-item box box__second">
                    <img class="case-item__icon" src="<?=$uri->site?>assets/icons/link-chain-svgrepo-com.svg" alt="" />
                    <div>
                      <h3 class="title title--h5">BlockChain Technology</h3>
                      <p class="case-item__caption">I am a BlockChain Developer (Solidity) trainee and I also trade CryptoCurrencies</p>
                    </div>
                  </div>
                </div>


              </div>
            </div>

            <div class="box-inner">
              <h2 class="title title--h3">Recent Projects</h2>

              <div class="swiper-container js-carousel-review">
                <div class="swiper-wrapper">
                  <div class="swiper-slide review-item">
                    <svg class="avatar avatar--80" viewBox="0 0 84 84">
                      <g class="avatar__hexagon">
                        <image xlink:href="https://ahiamoni.com/assets/picture/img/ahiamoni_icon_color_1.svg" height="100%" width="100%" />
                      </g>
                    </svg>
                    <h4 class="title title--h5">Ahiamoni</h4>
                    <p class="review-item__caption">Online Product Listing Services, Buy, Sell, Shop, Explore products and services, Advertize Etc.</p>
                  </div>

                  <div class="swiper-slide review-item">
                    <svg class="avatar avatar--80" viewBox="0 0 84 84">
                      <g class="avatar__hexagon">
                        <image xlink:href="https://apexforexglobal.com/assets/image/logos/logo.png" height="100%" width="100%" />
                      </g>
                    </svg>
                    <h4 class="title title--h5">Apex Forex Global</h4>
                    <p class="review-item__caption">Professional Trading Platform for Exchange rates, Foreign Exchange, Bitcoin, Crypto Investments</p>
                  </div>

                  <div class="swiper-slide review-item">
                    <svg class="avatar avatar--80" viewBox="0 0 84 84">
                      <g class="avatar__hexagon">
                        <image xlink:href="<?=$uri->site?>assets/icons/digital-dreams.png" height="auto" width="100%" />
                      </g>
                    </svg>
                    <h4 class="title title--h5">Digital Dreams LTD</h4>
                    <p class="review-item__caption">Company Website for the Biggest ICT Academy in Enugu</p>
                  </div>
                  <div class="swiper-slide review-item">
                    <svg class="avatar avatar--80" viewBox="0 0 84 84">
                      <g class="avatar__hexagon">
                        <image xlink:href="https://skylinkng.com/assets/img/logo.png"  width="100%" />
                      </g>
                    </svg>
                    <h4 class="title title--h5">Skylink Transport & Logistics</h4>
                    <p class="review-item__caption">A transport, travels and logistics services company</p>
                  </div>

                  <div class="swiper-slide review-item">
                    <svg class="avatar avatar--80" viewBox="0 0 84 84">
                      <g class="avatar__hexagon">
                        <image xlink:href="https://dgitpay.com/img/logo.png?v=maiyc" width="100%" />
                      </g>
                    </svg>
                    <h4 class="title title--h5">Dgit Pay</h4>
                    <p class="review-item__caption">Finacial Investments Company</p>
                  </div>
                </div>

                <div class="swiper-pagination"></div>
              </div>
            </div>

            <div class="box-inner">
              <h2 class="title title--h3">Hobbies</h2>

              <div class="swiper-container js-carousel-clients">
                <div class="swiper-wrapper">
                  <!-- Item client -->
                  <div class="swiper-slide" style="width:auto">
                    <strong>BlockChain Technology and News</strong>
                  </div>

                  <div class="swiper-slide">
                    <strong>Enjoy Working Out</strong>
                  </div>

                  <div class="swiper-slide">
                    <strong>Listening to Music</strong>
                  </div>
                </div>

                <div class="swiper-pagination"></div>
              </div>
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
