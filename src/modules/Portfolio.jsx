import React from 'react'
import profile from '../statics/profile'

export default function Portfolio() {
  const {portfolio}=profile;
  return (
    <div>
      <div className="pb-2">
        <h1 className="title title--h1 title__separate">Portfolio</h1>
      </div>

      <div className="news-grid pb-0">
       {
         portfolio.length > 0 && portfolio.map((project, index)=>(
            <article key={index} className="news-item box">
              <div className="news-item__image-wrap overlay overlay--45">
                <div className="news-item__date">{project.category}</div>
                <a className="news-item__link" href={project.disabled ? '#':project.link}></a>
                <img className="cover lazyload" src={project.image} alt="" />
              </div>
              <div className="news-item__caption">
                <h2 className="title title--h4">{project.title}</h2>
                <p>{project.description}</p>
              </div>
            </article>
         ))
       }

      </div>
    </div>
  )
}
