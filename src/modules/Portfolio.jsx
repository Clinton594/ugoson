import React from "react";
import profile from "../statics/profile";
import ScrollAnimation from "react-animate-on-scroll";
import { isEven } from "../statics/function";

export default function Portfolio() {
  const animation = ["fadeInRight", "fadeInLeft"];
  const exit = animation.map((style) => style.replace("In", "Out"));
  const { portfolio } = profile;
  return (
    <div id="portfolio">
      <ScrollAnimation animateIn="slideInDown" animateOut="slideOutUp">
        <h1 className="title title--h1 title__separate">Portfolio</h1>
      </ScrollAnimation>

      <div className="news-grid pb-0">
        {portfolio.length > 0 &&
          portfolio.map((project, index) => (
            <ScrollAnimation
              animateIn={animation[isEven(index)]}
              animateOut={exit[isEven(index)]}
              key={index}
              className="news-item box"
            >
              <div className="news-item__image-wrap overlay overlay--45">
                <div className="news-item__date">{project.category}</div>
                <a
                  target={project.disabled ? "_parent" : "_blank"}
                  className="news-item__link"
                  href={project.disabled ? "/" : project.link}
                ></a>
                <img className="cover lazyload" src={project.image} alt="" />
              </div>
              <div className="news-item__caption">
                <h2 className="title title--h4">{project.title}</h2>
                <p>
                  {project.description}
                  <br />(
                  <span>
                    <small>
                      <i> {project.stack}</i>
                    </small>
                  </span>
                  )
                </p>
              </div>
            </ScrollAnimation>
          ))}
      </div>
    </div>
  );
}
