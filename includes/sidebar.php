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
<!-- Sidebar -->
<aside class="col-12 col-md-12 col-xl-3">
  <div class="sidebar box shadow pb-0 sticky-column">
    <svg class="avatar avatar--180" viewBox="0 0 188 188">
      <g class="avatar__box">
        <image xlink:href="assets/images/IMG_-ywdefa.jpg" height="100%" width="100%" />
      </g>
    </svg>
    <div class="text-center">
      <h3 class="title title--h3 sidebar__user-name"><?=$generic->name?></h3>
      <div class="badge badge--dark">Fullstack Web Developer</div>

      <!-- Social -->
      <div class="social">
        <a class="social__link" href="https://www.facebook.com/"><i class="font-icon icon-facebook"></i></a>
        <a class="social__link" href="https://www.behance.com/"><i class="font-icon icon-twitter"></i></a>
        <a class="social__link" href="https://www.linkedin.com/in/clinton-onuigbo-a5112810b/"><i class="font-icon icon-linkedin2"></i></a>
      </div>
    </div>

    <div class="sidebar__info box-inner">
      <ul class="contacts-block"  style="margin-bottom:0px ">
        <li class="contacts-block__item" data-toggle="tooltip" data-placement="top" title="Address">
          <i class="font-icon icon-location"></i>Enugu State, Nigeria
        </li>
        <li class="contacts-block__item" data-toggle="tooltip" data-placement="top" title="E-mail">
          <a href="mailto:<?=$generic->email?>"><i class="font-icon icon-envelope"></i><?=$generic->email?></a>
        </li>
        <li class="contacts-block__item" data-toggle="tooltip" data-placement="top" title="Phone">
          <i class="font-icon icon-phone"></i><?=$generic->phones["MTN"]?>
        </li>
      </ul>
      <a class="btn" href="whatsapp://send?phone=<?=$generic->phones["GLO"]?>"><i class="font-icon icon-phone"></i> <small>Get in Touch (WhatsApp)</small>
      </a>
    </div>
  </div>
</aside>
