<!DOCTYPE html>
<html lang="en">
<head>
  <title>About </title>
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
                <li class="nav__item"><a class="active" href="about.html">About</a></li>
                <li class="nav__item"><a href="resume.html">Resume</a></li>
                <li class="nav__item"><a href="portfolio.html">Portfolio</a></li>
                <li class="nav__item"><a href="blog.html">Blog</a></li>
                <li class="nav__item"><a href="contact.html">Contact</a></li>
              </ul>
            </div>

            <!-- About -->
            <div class="pb-0 pb-sm-2">
              <h1 class="title title--h1 title__separate">About Me</h1>
              <p>I'm Creative Director and UI/UX Designer from Sydney, Australia, working in web development and print media. I enjoy turning complex problems into simple, beautiful and intuitive designs.</p>
              <p>My job is to build your website so that it is functional and user-friendly but at the same time attractive. Moreover, I add personal touch to your product and make sure that is eye-catching and easy to use. My aim is to bring across your message and identity in the most creative way. I created web design for many famous brand companies.</p>
            </div>

            <!-- What -->
            <div class="box-inner pb-0">
              <h2 class="title title--h3">What I'm Doing</h2>
              <div class="row">
                <!-- Case Item -->
                <div class="col-12 col-lg-6">
                  <div class="case-item box box__second">
                    <img class="case-item__icon" src="http://netgon.net/artstyles/v-card/assets/icons/icon-design.svg" alt="" />
                    <div>
                      <h3 class="title title--h5">Web Design</h3>
                      <p class="case-item__caption">The most modern and high-quality design made at a professional level.</p>
                    </div>
                  </div>
                </div>

                <!-- Case Item -->
                <div class="col-12 col-lg-6">
                  <div class="case-item box box__second">
                    <img class="case-item__icon" src="http://netgon.net/artstyles/v-card/assets/icons/icon-dev.svg" alt="" />
                    <div>
                      <h3 class="title title--h5">Web Development</h3>
                      <p class="case-item__caption">High-quality and professional development of sites at the professional level.</p>
                    </div>
                  </div>
                </div>

                <!-- Case Item -->
                <div class="col-12 col-lg-6">
                  <div class="case-item box box__second">
                    <img class="case-item__icon" src="http://netgon.net/artstyles/v-card/assets/icons/icon-app.svg" alt="" />
                    <div>
                      <h3 class="title title--h5">Mobile Apps</h3>
                      <p class="case-item__caption">Professional development of applications for iOS and Android.</p>
                    </div>
                  </div>
                </div>

                <!-- Case Item -->
                <div class="col-12 col-lg-6">
                  <div class="case-item box box__second">
                    <img class="case-item__icon" src="http://netgon.net/artstyles/v-card/assets/icons/icon-photo.svg" alt="" />
                    <div>
                      <h3 class="title title--h5">Photography</h3>
                      <p class="case-item__caption">I make high-quality photos of any category at a professional level.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Testimonials -->
            <div class="box-inner">
              <h2 class="title title--h3">Testimonials</h2>

              <div class="swiper-container js-carousel-review">
                <div class="swiper-wrapper">
                  <!-- Item review -->
                  <div class="swiper-slide review-item">
                    <svg class="avatar avatar--80" viewBox="0 0 84 84">
                      <g class="avatar__hexagon">
                        <image xlink:href="assets/img/avatar-2.jpg" height="100%" width="100%" />
                      </g>
                    </svg>
                    <h4 class="title title--h5">Daniel Lewis</h4>
                    <p class="review-item__caption">Felecia was hired to create a corporate identity. We were very pleased with the work done.</p>
                  </div>

                  <!-- Item review -->
                  <div class="swiper-slide review-item">
                    <svg class="avatar avatar--80" viewBox="0 0 84 84">
                      <g class="avatar__hexagon">
                        <image xlink:href="assets/img/avatar-3.jpg" height="100%" width="100%" />
                      </g>
                    </svg>
                    <h4 class="title title--h5">Jessica Miller</h4>
                    <p class="review-item__caption">Thanks to the skill of Felecia, we have a design that we can be proud of.</p>
                  </div>

                  <!-- Item review -->
                  <div class="swiper-slide review-item">
                    <svg class="avatar avatar--80" viewBox="0 0 84 84">
                      <g class="avatar__hexagon">
                        <image xlink:href="assets/img/avatar-4.jpg" height="100%" width="100%" />
                      </g>
                    </svg>
                    <h4 class="title title--h5">Tanya Ruiz</h4>
                    <p class="review-item__caption">Felecia was hired to create a corporate identity. We were very pleased with the work done.</p>
                  </div>

                  <!-- Item review -->
                  <div class="swiper-slide review-item">
                    <svg class="avatar avatar--80" viewBox="0 0 84 84">
                      <g class="avatar__hexagon">
                        <image xlink:href="assets/img/avatar-5.jpg" height="100%" width="100%" />
                      </g>
                    </svg>
                    <h4 class="title title--h5">Thomas Castro</h4>
                    <p class="review-item__caption">Thanks to the skill of Felecia, we have a design that we can be proud of.</p>
                  </div>
                </div>

                <div class="swiper-pagination"></div>
              </div>
            </div>

            <!-- Clients -->
            <div class="box-inner">
              <h2 class="title title--h3">Clients</h2>

              <div class="swiper-container js-carousel-clients">
                <div class="swiper-wrapper">
                  <!-- Item client -->
                  <div class="swiper-slide">
                    <a href="#"><img src="http://netgon.net/artstyles/v-card/assets/img/logo-partner-white.svg" alt="Logo" /></a>
                  </div>

                  <!-- Item client -->
                  <div class="swiper-slide">
                    <a href="#"><img src="http://netgon.net/artstyles/v-card/assets/img/logo-partner-white.svg" alt="Logo" /></a>
                  </div>

                  <!-- Item client -->
                  <div class="swiper-slide">
                    <a href="#"><img src="http://netgon.net/artstyles/v-card/assets/img/logo-partner-white.svg" alt="Logo" /></a>
                  </div>

                  <!-- Item client -->
                  <div class="swiper-slide">
                    <a href="#"><img src="http://netgon.net/artstyles/v-card/assets/img/logo-partner-white.svg" alt="Logo" /></a>
                  </div>

                  <!-- Item client -->
                  <div class="swiper-slide">
                    <a href="#"><img src="http://netgon.net/artstyles/v-card/assets/img/logo-partner-white.svg" alt="Logo" /></a>
                  </div>

                  <!-- Item client -->
                  <div class="swiper-slide">
                    <a href="#"><img src="http://netgon.net/artstyles/v-card/assets/img/logo-partner-white.svg" alt="Logo" /></a>
                  </div>
                </div>

                <div class="swiper-pagination"></div>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <footer class="footer">© 2019 vCard</footer>
        </div>
      </div>
    </div>
  </main>

  <div class="back-to-top"></div>

  <!-- SVG masks -->
  <svg class="svg-defs">
    <clipPath id="avatar-box">
      <path d="M1.85379 38.4859C2.9221 18.6653 18.6653 2.92275 38.4858 1.85453 56.0986.905299 77.2792 0 94 0c16.721 0 37.901.905299 55.514 1.85453 19.821 1.06822 35.564 16.81077 36.632 36.63137C187.095 56.0922 188 77.267 188 94c0 16.733-.905 37.908-1.854 55.514-1.068 19.821-16.811 35.563-36.632 36.631C131.901 187.095 110.721 188 94 188c-16.7208 0-37.9014-.905-55.5142-1.855-19.8205-1.068-35.5637-16.81-36.63201-36.631C.904831 131.908 0 110.733 0 94c0-16.733.904831-37.9078 1.85379-55.5141z"/>
    </clipPath>
    <clipPath id="avatar-hexagon">
      <path d="M0 27.2891c0-4.6662 2.4889-8.976 6.52491-11.2986L31.308 1.72845c3.98-2.290382 8.8697-2.305446 12.8637-.03963l25.234 14.31558C73.4807 18.3162 76 22.6478 76 27.3426V56.684c0 4.6805-2.5041 9.0013-6.5597 11.3186L44.4317 82.2915c-3.9869 2.278-8.8765 2.278-12.8634 0L6.55974 68.0026C2.50414 65.6853 0 61.3645 0 56.684V27.2891z"/>
    </clipPath>
  </svg>

</body>
</html>
