import React from 'react'
import profile from '../statics/profile'

export default function AboutMe() {

  return (
    <>
      <div className="pb-0 pb-sm-2">
        <h1 className="title title--h1 title__separate">About Me</h1>
        <div >
          {profile.bio.split(".").map((element, index)=>(
            <p key={index}>{`${element}.`}</p>
          ))} 
        </div>
      </div>

      <div className="box-inner pb-0">
        <h2 className="title title--h5">What I Do</h2>
        <div className="row">
          {
            profile.features.length > 0 && profile.features.map((feature, index)=>(
              <div key={index} className="col-12 col-lg-6">
                <div className="case-item box box__second">
                  <img className="case-item__icon" src={feature.image} alt="" />
                  <div>
                    <h3 className="title title--h5">{feature.title}</h3>
                    <p className="case-item__caption">{feature.description}</p>
                  </div>
                </div>
              </div>
            ))
          }
        </div>
      </div>

    

      {/* <div className="box-inner">
        <h2 className="title title--h3">Hobbies</h2>

        <div className="swiper-container js-carousel-clients">
          <div className="swiper-wrapper">
            <div className="swiper-slide" style={{width:"auto"}}>
              <strong>BlockChain Technology and News</strong>
            </div>

            <div className="swiper-slide">
              <strong>Enjoy Working Out</strong>
            </div>

            <div className="swiper-slide">
              <strong>Listening to Music</strong>
            </div>
          </div>

          <div className="swiper-pagination"></div>
        </div>
      </div> */}
    </>
  )
}
