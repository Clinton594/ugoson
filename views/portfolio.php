<!DOCTYPE html>
<html lang="en">
<head>
  <title>vCard - Portfolio</title>
  <?php require_once 'includes/links.php'; ?>
</head>
<body class="bg-triangles">
  <!-- Preloader -->
  <div class="preloader">
    <div class="preloader__wrap">
      <div class="circle-pulse">
        <div class="circle-pulse__1"></div>
        <div class="circle-pulse__2"></div>
      </div>
      <div class="preloader__progress"><span></span></div>
    </div>
  </div>

  <main class="main">
    <div class="container gutter-top">
      <div class="row sticky-parent">
        <!-- Sidebar -->
        <aside class="col-12 col-md-12 col-xl-3">
          <div class="sidebar box shadow pb-0 sticky-column">
            <svg class="avatar avatar--180" viewBox="0 0 188 188">
              <g class="avatar__box">
                <image xlink:href="assets/img/avatar-1.jpg" height="100%" width="100%" />
              </g>
            </svg>
            <div class="text-center">
              <h3 class="title title--h3 sidebar__user-name"><span class="weight--500">Felecia</span> Brown</h3>
              <div class="badge badge--dark">Creative Director</div>

              <!-- Social -->
              <div class="social">
                <a class="social__link" href="https://www.facebook.com/"><i class="font-icon icon-facebook"></i></a>
                <a class="social__link" href="https://www.behance.com/"><i class="font-icon icon-twitter"></i></a>
                <a class="social__link" href="https://www.linkedin.com/"><i class="font-icon icon-linkedin2"></i></a>
              </div>
            </div>

            <div class="sidebar__info box-inner">
              <ul class="contacts-block">
                <li class="contacts-block__item" data-toggle="tooltip" data-placement="top" title="Birthday">
                  <i class="font-icon icon-calendar"></i>March 12, 1995
                </li>
                <li class="contacts-block__item" data-toggle="tooltip" data-placement="top" title="Address">
                  <i class="font-icon icon-location"></i>Hong Kong, China
                </li>
                <li class="contacts-block__item" data-toggle="tooltip" data-placement="top" title="E-mail">
                  <a href="mailto:example@mail.com"><i class="font-icon icon-envelope"></i>example@mail.com</a>
                </li>
                <li class="contacts-block__item" data-toggle="tooltip" data-placement="top" title="Phone">
                  <i class="font-icon icon-phone"></i>+1 (070) 123-4567
                </li>
                <li class="contacts-block__item" data-toggle="tooltip" data-placement="top" title="Skype">
                  <a href="skype:skype-example"><i class="font-icon icon-skype"></i>Felecia_Brown</a>
                </li>
              </ul>

              <a class="btn" href="#"><i class="font-icon icon-download"></i> Download CV</a>
            </div>
          </div>
        </aside>

        <!-- Content -->
        <div class="col-12 col-md-12 col-xl-9">
          <div class="box shadow pb-0">
            <!-- Menu -->
            <div class="circle-menu">
              <div class="hamburger">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
              </div>
            </div>
            <div class="inner-menu js-menu">
              <ul class="nav">
                <li class="nav__item"><a href="about.html">About</a></li>
                <li class="nav__item"><a href="resume.html">Resume</a></li>
                <li class="nav__item"><a class="active" href="portfolio.html">Portfolio</a></li>
                <li class="nav__item"><a href="blog.html">Blog</a></li>
                <li class="nav__item"><a href="contact.html">Contact</a></li>
              </ul>
            </div>

            <!-- About -->
            <div class="pb-2">
              <h1 class="title title--h1 title__separate">Portfolio</h1>
            </div>

            <!-- Gallery -->
            <div class="pb-0">
              <!-- Filter -->
              <div class="select">
                <span class="placeholder">Select category</span>
                <ul class="filter">
                  <li class="filter__item">Category</li>
                  <li class="filter__item active" data-filter="*"><a class="filter__link active" href="#filter">All</a></li>
                  <li class="filter__item" data-filter=".category-concept"><a class="filter__link" href="#filter">Concept</a></li>
                  <li class="filter__item" data-filter=".category-design"><a class="filter__link" href="#filter">Design</a></li>
                  <li class="filter__item" data-filter=".category-life"><a class="filter__link" href="#filter">Life</a></li>
                </ul>
                <input type="hidden" name="changemetoo"/>
              </div>

              <!-- Content -->
              <div class="gallery-grid js-masonry js-filter-container">
                <div class="gutter-sizer"></div>
                <!-- Item 1 -->
                <figure class="gallery-grid__item category-concept">
                  <div class="gallery-grid__image-wrap">
                    <img class="gallery-grid__image cover lazyload" src="assets/img/image_01.jpg" data-zoom alt="" />
                  </div>
                  <figcaption class="gallery-grid__caption">
                    <h4 class="title gallery-grid__title">Half Avocado</h4>
                    <span class="gallery-grid__category">Concept</span>
                  </figcaption>
                </figure>

                <!-- Item 2 -->
                <figure class="gallery-grid__item category-concept">
                  <div class="gallery-grid__image-wrap">
                    <img class="gallery-grid__image cover lazyload" src="assets/img/image_02.jpg" data-zoom alt="" />
                  </div>
                  <figcaption class="gallery-grid__caption">
                    <h4 class="title gallery-grid__title">Pink Flamingo</h4>
                    <span class="gallery-grid__category">Concept</span>
                  </figcaption>
                </figure>

                <!-- Item 3 -->
                <figure class="gallery-grid__item category-design">
                  <div class="gallery-grid__image-wrap">
                    <img class="gallery-grid__image cover lazyload" src="assets/img/image_03.jpg" data-zoom alt="" />
                  </div>
                  <figcaption class="gallery-grid__caption">
                    <h4 class="title gallery-grid__title">Abstract</h4>
                    <span class="gallery-grid__category">Design</span>
                  </figcaption>
                </figure>

                <!-- Item 4 -->
                <figure class="gallery-grid__item category-design">
                  <div class="gallery-grid__image-wrap">
                    <img class="gallery-grid__image cover lazyload" src="assets/img/image_04.jpg" data-zoom alt="" />
                  </div>
                  <figcaption class="gallery-grid__caption">
                    <h4 class="title gallery-grid__title">Abstract #2</h4>
                    <span class="gallery-grid__category">Design</span>
                  </figcaption>
                </figure>

                <!-- Item 5 -->
                <figure class="gallery-grid__item category-design">
                  <div class="gallery-grid__image-wrap">
                    <img class="gallery-grid__image cover lazyload" src="assets/img/image_05.jpg" data-zoom alt="" />
                  </div>
                  <figcaption class="gallery-grid__caption">
                    <h4 class="title gallery-grid__title">Abstract #3</h4>
                    <span class="gallery-grid__category">Design</span>
                  </figcaption>
                </figure>

                <!-- Item 6 -->
                <figure class="gallery-grid__item category-life">
                  <div class="gallery-grid__image-wrap">
                    <img class="gallery-grid__image cover lazyload" src="assets/img/image_06.jpg" data-zoom alt="" />
                  </div>
                  <figcaption class="gallery-grid__caption">
                    <h4 class="title gallery-grid__title">Golden Gate</h4>
                    <span class="gallery-grid__category">Life</span>
                  </figcaption>
                </figure>

                <!-- Item 7 -->
                <figure class="gallery-grid__item category-concept">
                  <div class="gallery-grid__image-wrap">
                    <img class="gallery-grid__image cover lazyload" src="assets/img/image_07.jpg" data-zoom alt="" />
                  </div>
                  <figcaption class="gallery-grid__caption">
                    <h4 class="title gallery-grid__title">Peach</h4>
                    <span class="gallery-grid__category">Concept</span>
                  </figcaption>
                </figure>

                <!-- Item 8 -->
                <figure class="gallery-grid__item category-design">
                  <div class="gallery-grid__image-wrap">
                    <img class="gallery-grid__image cover lazyload" src="assets/img/image_08.jpg" data-zoom alt="" />
                  </div>
                  <figcaption class="gallery-grid__caption">
                    <h4 class="title gallery-grid__title">Abstract #4</h4>
                    <span class="gallery-grid__category">Design</span>
                  </figcaption>
                </figure>

                <!-- Item 9 -->
                <figure class="gallery-grid__item category-life">
                  <div class="gallery-grid__image-wrap">
                    <img class="gallery-grid__image cover lazyload" src="assets/img/image_09.jpg" data-zoom alt="" />
                  </div>
                  <figcaption class="gallery-grid__caption">
                    <h4 class="title gallery-grid__title">Hedgehog</h4>
                    <span class="gallery-grid__category">Life</span>
                  </figcaption>
                </figure>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <footer class="footer">© 2019 vCard</footer>
        </div>
      </div>
    </div>
  </main>

  
</body>
</html>
