import React from "react";

export default function MiniTools() {
  return (
    <div>
      {" "}
      <div className="box-inner">
        <h2 className="title title--h3">My Personal Tools | Plugins</h2>

        <div className="swiper-container js-carousel-review">
          <div className="swiper-wrapper">
            <div className="swiper-slide review-item">
              <a href="http://whatsapp.ugoson.com/">
                <svg className="avatar avatar--80" viewBox="0 0 84 84">
                  <g className="avatar__hexagon">
                    <image
                      xlink:href="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/479px-WhatsApp.svg.png"
                      height="100%"
                      width="100%"
                    />
                  </g>
                </svg>
                <h4 className="title title--h5">WhatsApp Instant Chat</h4>
                <p className="review-item__caption">
                  No need to worry about saving stranger's contacts anymore on your phone, just generate a link to
                  directly chat with them.
                </p>
              </a>
            </div>

            <div className="swiper-slide review-item">
              <a href="http://contacts.ugoson.com/">
                <svg className="avatar avatar--80" viewBox="0 0 84 84">
                  <g className="avatar__hexagon">
                    <image xlink:href="https://www.svgrepo.com/show/52799/phone.svg" height="100%" width="100%" />
                  </g>
                </svg>
                <h4 className="title title--h5">Phone Number Extractor</h4>
                <p className="review-item__caption">
                  Neatly Extract phone numbers from a 'txt', 'vcf', 'doc', 'docx' file and choose how you format them
                  for easier bulk SMS.
                </p>
              </a>
            </div>

            <div className="swiper-slide review-item">
              <a href="http://html2jquery.ugoson.com/">
                <svg className="avatar avatar--80" viewBox="0 0 84 84">
                  <g className="avatar__hexagon">
                    <image
                      xlink:href="https://www.svgrepo.com/show/303205/html-5-logo.svg"
                      height="auto"
                      width="100%"
                    />
                  </g>
                </svg>
                <h4 className="title title--h5">HTML to JQuery Converter</h4>
                <p className="review-item__caption">
                  Convert your HTML markup codes to JQuery code in an instant. Just copy and paste, then you'll get your
                  result.
                </p>
              </a>
            </div>
          </div>

          <div className="swiper-pagination"></div>
        </div>
      </div>
    </div>
  );
}
