import Preloader from "./modules/Preloader"; 
import Sidebar from "./modules/Sidebar"; 
// import Navbar from "./modules/Navbar";
// import Footer from "./modules/Footer";
function App() {
  return (
    <main className="main">
      <div className="container gutter-top">
        <div className="row sticky-parent">

          <Preloader/>
          <Sidebar/>

          <div className="col-12 col-md-12 col-xl-9">
            <div className="box shadow pb-0">

              {/* <Navbar/> */}
              {/* <Footer/> */}    
            </div>
          </div>
        </div>
      </div>
    </main>
  );
}

export default App;
