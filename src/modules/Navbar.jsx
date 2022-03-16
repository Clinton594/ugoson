import React from 'react'

export default function Navbar() {
  return (
    <>
      <div className="js-menu mt-4 d-none d-lg-block">
        <ul className="nav">
          <li className="nav__item"><a className="active" href="#about">About</a></li>
          <li className="nav__item"><a href="#about">Resume</a></li>
          <li className="nav__item"><a href="#about">Portfolio</a></li>
        </ul>
      </div>
    </ >
  )
}
