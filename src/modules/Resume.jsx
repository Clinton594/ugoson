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
            <span className="timeline__period">April 2022 — Present (Contract)</span>
            <p className="timeline__description">
              • Working on Client Projects
              <br />
              • Developed mobile friendly web application with payment integrations.
              <br />
              • Working with developers to design algorithms and flowcharts.
              <br />
            </p>
            <i className="text-muted">
              <small>- Using JavaScript, TypeScript, React.js, Redux, HTML5, CSS3</small>
            </i>
            <hr />
          </article>

          <article className="timeline__item">
            <h5 className="title title--h5 timeline__title">Co-Founder at HACKPIY BLOCKCHAIN ACADEMY</h5>
            <span className="timeline__period">Apr 2021 - Present (Self Employed)</span>
            <p className="timeline__description">
              • Assembled competent workforce to execute company vision.
              <br />
              • Leveraged available resources and worked creatively with limited infrastructure.
              <br />
              • Developed blockchain applications for our clients.
              <br />
            </p>

            <i className="text-muted">
              <small>
                - Using Solidity, Ganache/Truffle, Web3.js, Ethers.js, React.js, Redux, JavaScript, TypeScript, Next.js,
                Node.js, Express.js, Mongodb, HTML5, CSS3
              </small>
            </i>
            <hr />
          </article>
          <article className="timeline__item">
            <h5 className="title title--h5 timeline__title">SENIOR SOFTWARE DEVELOPER at DIGTIAL DREAMS LTD</h5>
            <span className="timeline__period">Oct 2018 — Mar 2021 (Full Time)</span>
            <p className="timeline__description">
              • Led an 11-man team to develop highly scalable applications.
              <br />
              • Applied web services and REST API design best practices to deliver stable systems.
              <br />
              • - Collaborated with customers to identify and resolve issues.
              <br />
            </p>
            <i className="text-muted">
              <small>- Using CSS3, HTML5, JavaScript, jQuery, PHP, MYQL, React.js, Redux</small>
            </i>
            <hr />
          </article>
          <article className="timeline__item">
            <h5 className="title title--h5 timeline__title">JUNIOR WEB DEVELOPER at DIGTIAL DREAMS LTD</h5>
            <span className="timeline__period">May 2017 — Oct 2018 (Full Time)</span>
            <p className="timeline__description">
              • Developer Intern for 1 year.
              <br />
              • Collaborated with customers to identify and resolve issues.
              <br />
              • Training and coaching students in the development processes of their projects.
              <br />
              <i className="text-muted">
                <small>- Using CSS3, HTML5, JavaScript, jQuery, PHP, MYQL</small>
              </i>
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
            <h5 className="title title--h5 timeline__title">UDEMY - ADVANCED NODE JS CONCEPTS</h5>
            <span className="timeline__period">MAY 2022 - Present</span>
            <p className="timeline__description">
              • Stephen Grider <br />
              <br />
            </p>
          </article>
          <article className="timeline__item">
            <h5 className="title title--h5 timeline__title">
              UDEMY - JAVASCRIPT ALGORITHMS & DATA STRUCTURES MASTERCLASS
            </h5>
            <span className="timeline__period">2021</span>
            <p className="timeline__description">
              • Stephen Grider <br />
              <br />
            </p>
          </article>
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
