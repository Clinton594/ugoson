import React from 'react'
import profile from '../statics/profile'

export default function Portfolio() {
  const {portfolio}=profile;
  return (
    <div>
      <div class="pb-2">
        <h1 class="title title--h1 title__separate">Portfolio</h1>
      </div>

      <div class="news-grid pb-0">
       {
         portfolio.length > 0 && portfolio.map((project, index)=>(
            <article key={index} class="news-item box">
              <div class="news-item__image-wrap overlay overlay--45">
                <div class="news-item__date">{project.category}</div>
                <a class="news-item__link" href={project.disabled ? '#':project.link}></a>
                <img class="cover lazyload" src={project.image} alt="" />
              </div>
              <div class="news-item__caption">
                <h2 class="title title--h4">{project.title}</h2>
                <p>{project.description}</p>
              </div>
            </article>
         ))
       }

      </div>
    </div>
  )
}
