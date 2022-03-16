import React from 'react'

export default function Events() {
  return (
    <div class="row">
      <div class="col-md-12">
        <h2 class="title title--h3">
          <img class="title-icon" src="assets/icons/add-svgrepo-com.svg" alt="" />
          Leadership Events
        </h2>
        <div class="row gallery-grid js-masonry js-filter-container">

          <figure class="col-lg-4 gallery-grid__item category-concept">
            <div class="gallery-grid__image-wrap">
              <img class="gallery-grid__image cover lazyload" src="assets/images/gdg.jpeg" data-zoom alt="" />
            </div>
            <figcaption class="gallery-grid__caption">
              <h4 class="title gallery-grid__title">Google Business Group Enugu (GBG) 2019</h4>
              <span class="gallery-grid__category">Speaker on Google's Accelerated Mobile Pages (AMP)</span>
            </figcaption>
          </figure>

          <figure class="col-lg-4 gallery-grid__item category-concept">
            <div class="gallery-grid__image-wrap">
              <img class="gallery-grid__image cover lazyload" src="assets/images/dreamshub.jpeg" data-zoom alt="" />
            </div>
            <figcaption class="gallery-grid__caption">
              <h4 class="title gallery-grid__title">Dreams ICT Tech Hub</h4>
              <span class="gallery-grid__category">Project Reviews with the Interns at Dreams Hub, Enugu</span>
            </figcaption>
          </figure>

          <figure class="col-lg-4 gallery-grid__item category-concept">
            <div class="gallery-grid__image-wrap">
              <img class="gallery-grid__image cover lazyload" src="assets/images/intern.jpeg" data-zoom alt="" />
            </div>
            <figcaption class="gallery-grid__caption">
              <h4 class="title gallery-grid__title">Heading BootCamp program at Dreams Tech Hub</h4>
              <span class="gallery-grid__category">Handled the latest batch of Developer Interns for Digital Dreams</span>
            </figcaption>
          </figure>
        </div>
      </div>
    </div>
  )
}
