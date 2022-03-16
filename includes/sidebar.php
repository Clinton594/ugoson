<!-- Preloader -->

<!-- Sidebar -->
<aside class="col-12 col-md-12 col-xl-3">
  <div class="sidebar box shadow pb-0 sticky-column">
    <svg class="avatar avatar--180" viewBox="0 0 188 188">
      <g class="avatar__box">
        <image xlink:href="assets/images/IMG_-ywdefa.jpg" height="100%" width="100%" />
      </g>
    </svg>
    <div class="text-center">
      <h3 class="title title--h3 sidebar__user-name"><?= $generic->name ?></h3>
      <div class="badge badge--dark">Fullstack Web Developer</div>
    </div>

    <div class="sidebar__info box-inner">
      <ul class="contacts-block" style="margin-bottom:0px ">
        <li class="contacts-block__item" data-toggle="tooltip" data-placement="top" title="linkedIn">
          <a href="https://www.linkedin.com/in/clinton-onuigbo-a5112810b/"><i class="font-icon icon-phone"></i>linkedIn Profile</a>
        </li>
        <li class="contacts-block__item" data-toggle="tooltip" data-placement="top" title="WhatsApp">
          <a href="whatsapp://send?phone=<?= $generic->phones["GLO"] ?>"><i class="font-icon icon-phone"></i>WhatsApp</a>
        </li>
        <li class="contacts-block__item" data-toggle="tooltip" data-placement="top" title="Phone">
          <a href="tel://<?= $generic->phones["GLO"] ?>"><i class="font-icon icon-phone"></i><?= $generic->phones["GLO"] ?></a>
        </li>
        <li class="contacts-block__item" data-toggle="tooltip" data-placement="top" title="Facebook">
          <a href="https://web.facebook.com/Clinton594"><i class="font-icon icon-facebook"></i> Facebook Profile</a>
        </li>
      </ul>
      <a class="btn" href="mailto:<?= $generic->email ?>"><i class="font-icon icon-envelope"></i> <small>Get in Touch [Email]</small>
      </a>
    </div>
  </div>
</aside>