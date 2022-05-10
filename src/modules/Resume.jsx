import React from "react";
import ScrollAnimation from "react-animate-on-scroll";

export default function Resume() {
  return (
    <div className="row" id="resume">
      <div className="col-sm-12">
        <ScrollAnimation animateIn="slideInDown" animateOut="slideOutUp">
          <h1 className="title title--h1 title__separate">Resume</h1>
        </ScrollAnimation>
      </div>
      <div className="col-12 col-lg-6">
        <h2 className="title title--h3">
          <img className="title-icon" src="assets/icons/icon-experience.svg" alt="" /> Experience
        </h2>
        <div className="timeline">
          <article className="timeline__item">
            <h5 className="title title--h5 timeline__title">SENIOR SOFTWARE DEVELOPER at TUNGA.IO</h5>
            <span className="timeline__period">April 2022 — Present</span>
            <p className="timeline__description">
              • Working on Client Projects
              <br />
              • Designing and Deploying Micro Services.
              <br />
            </p>
            <hr />
          </article>
          <article className="timeline__item">
            <h5 className="title title--h5 timeline__title">SENIOR SOFTWARE DEVELOPER at DIGTIAL DREAMS LTD</h5>
            <span className="timeline__period">Oct 2018 — Mar 2021</span>
            <p className="timeline__description">
              • Team Lead.
              <br />
              • Handles all software related issues from the Software Department.
              <br />• Assign tasks and handle reportS.
            </p>
            <hr />
          </article>

          <article className="timeline__item">
            <h5 className="title title--h5 timeline__title">JUNIOR WEB DEVELOPER at DIGTIAL DREAMS LTD</h5>
            <span className="timeline__period">May 2017 — Oct 2018</span>
            <p className="timeline__description">
              • Developing and Maintaining the company’s Software Applications
              <br />
              • Developing and Maintaining Client’s Software
              <br />
              • Technical support on any assigned software project
              <br />
            </p>
            <hr />
          </article>

          <article className="timeline__item">
            <h5 className="title title--h5 timeline__title">
              INDUSTRIAL TRAINEE at NORTH-SOUTH POWER COMPANY LTD, SHIRORO NIGER STATE
            </h5>
            <span className="timeline__period">Apr 2016 — Apr 2017</span>
            <p className="timeline__description">
              • Was an ICT and Science subject’s teacher for their staff school. • Underwent trainings at the power
              station on the working principles of a turbine. • Remotely working for Digital Dreams during my service
              year.
            </p>
            <hr />
          </article>

          <article className="timeline__item">
            <h5 className="title title--h5 timeline__title">TRAINEE INTERN at DIGITAL DEAMS LTD</h5>
            <span className="timeline__period">Nov 2015 — Apr 2016</span>
            <p className="timeline__description">
              • 6months internship on Web Development <br />• Working on clients’ projects while learning
            </p>
            <hr />
          </article>
        </div>
      </div>
      <div className="col-12 col-lg-6">
        <h2 className="title title--h3">
          <img className="title-icon" src="assets/icons/icon-education.svg" alt="" /> Education
        </h2>
        <div className="timeline">
          <article className="timeline__item">
            <h5 className="title title--h5 timeline__title">ENUGU STATE UNIVERSITY OF SCIENCE AND TECHNOLOGY</h5>
            <span className="timeline__period">2010 — 2015</span>
            <p className="timeline__description">
              • Tertiary Education <br />
              • Mechanical Engineering
              <br />
              • B.Eng.
              <br />• C.G.P Available on request.
            </p>
          </article>

          <article className="timeline__item">
            <h5 className="title title--h5 timeline__title">SACRED HEART SEMINARY NSUDE, ENUGU STATE</h5>
            <span className="timeline__period">2003 — 2009</span>
            <p className="timeline__description">
              • Secondary Education <br />
              • Sciences <br />
              • SSCE <br />• Results available on request.
            </p>
          </article>
        </div>
      </div>
    </div>
  );
}
