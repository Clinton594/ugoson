import React from "react";
import profile from "../statics/profile";
import ScrollAnimation from "react-animate-on-scroll";
import { isEven } from "../statics/function";

export default function Competence() {
  const animation = ["fadeInRight", "fadeInLeft"];
  const exit = animation.map((style) => style.replace("In", "Out"));

  const { keyCompetence } = profile;
  return (
    <div className="col-12 col-md-12 col-xl-12">
      <div className="pb-3">
        <h1 className="title title--h1 title__separate">Competence</h1>
      </div>

      <div className="box-inner box-inner--rounded">
        <div className="row">
          <div className="col-12">
            <h2 className="title title--h5">Programming Skills</h2>
          </div>
          {keyCompetence.length > 0 &&
            keyCompetence.map((set, _index) => (
              <div className="col-12 col-lg-6" key={_index}>
                <ScrollAnimation animateIn={animation[isEven(_index)]} animateOut={exit[isEven(_index)]}>
                  <div className="box box__second mb-3">
                    <h6 className="mb-2">{set.title}</h6>
                    {set.list.length > 0 &&
                      set.list.map((skill, __index) => (
                        <div className="progress" key={__index}>
                          <div
                            className="progress-bar"
                            role="progressbar"
                            style={{ width: `${skill.level}%` }}
                            aria-valuemin="0"
                            aria-valuemax="100"
                          >
                            <div className="progress-text">
                              <span>{skill.language}</span>
                              <span>{skill.level}%</span>
                            </div>
                          </div>
                        </div>
                      ))}
                  </div>
                </ScrollAnimation>
              </div>
            ))}
        </div>
      </div>
    </div>
  );
}
