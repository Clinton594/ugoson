import Preloader from "./modules/Preloader"; 
import Sidebar from "./modules/Sidebar"; 
import Navbar from "./modules/Navbar";
import Footer from "./modules/Footer";
import About from "./modules/AboutMe";
import Competence from "./modules/Competence";
import Resume from "./modules/Resume";
import Portfolio from "./modules/Portfolio";
import Events from "./modules/Events";
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
              <About/>
              <Competence/>
              <Resume/>
              <Events/>
              <Portfolio/>
            </div>
          </div>
          <Footer/>
        </div>
      </div>
    </main>
  );
}

export default App;
