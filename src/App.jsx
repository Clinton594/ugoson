import Preloader from "./modules/Preloader"; 
import Sidebar from "./modules/Sidebar"; 
import Navbar from "./modules/Navbar";
import Footer from "./modules/Footer";
import About from "./modules/AboutMe";
import Competence from "./modules/Competence";
import Resume from "./modules/Resume";
import Portfolio from "./modules/Portfolio";
import Events from "./modules/Events";
import { BrowserView, MobileView, isMobile } from "react-device-detect";
import ScrollAnimation from "react-animate-on-scroll";
import { scroller, $ } from "./statics/function";
import { useEffect } from "react";


function App() {
  useEffect(()=>{
    scroller($(".sidebar"))
  }, [])
  return (
    <main className="main">
      <div className="container gutter-top">
        <div className="row sticky-parent">

          <Preloader/>
          <Sidebar/>

          <div className="col-12 col-md-12 col-xl-9">
            <div className="box shadow pb-0">
              <Navbar/>
              <ScrollAnimation animateIn="slideInDown" animateOut="slideOutUp">
                <About/>
              </ScrollAnimation>
              <ScrollAnimation animateIn={isMobile ? "slideInDown" : "flipInY"} animateOut={isMobile ? "slideOutUp" : "flipOutY"}>
                <Competence/>
              </ScrollAnimation>
              <ScrollAnimation animateIn={isMobile ? "slideInDown" : "flipInY"} animateOut={isMobile ? "slideOutUp" : "flipOutY"}>
                <Resume/>
              </ScrollAnimation>
              <ScrollAnimation animateIn={isMobile ? "slideInDown" : "flipInY"} animateOut={isMobile ? "slideOutUp" : "flipOutY"}>
                <Events/>
              </ScrollAnimation>
              <ScrollAnimation animateIn={isMobile ? "slideInDown" : "flipInY"} animateOut={isMobile ? "slideOutUp" : "flipOutY"}>
                <Portfolio/>
              </ScrollAnimation>
            </div>
          </div>
          <Footer/>
        </div>
      </div>
    </main>
  );
}

export default App;
