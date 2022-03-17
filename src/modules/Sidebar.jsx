import React from "react";
import profile from "../statics/profile";
import ScrollAnimation from "react-animate-on-scroll";

export default function Sidebar() {
  return (
    <aside className="col-12 col-md-12 col-xl-3">
      <div className="sidebar box shadow pb-0 sticky-column">
        <figure className="avatar avatar--180">
          <img className="bordered" src={profile.picture} alt="" />
        </figure>
        <div className="text-center">
          <h3 className="title title--h3 sidebar__user-name">{profile.name}</h3>
          <div className="badge badge--dark">{profile.role}</div>
        </div>

        <div className="sidebar__info box-inner">
          <ul className="contacts-block text-left" style={{ marginBottom: 0 }}>
            <li className="contacts-block__item" data-toggle="tooltip" data-placement="top" title="linkedIn">
              <a href="https://www.linkedin.com/in/clinton-onuigbo-a5112810b/">
                <i className="fab fa-linkedin mr-2"></i>LinkedIn Profile
              </a>
            </li>
            <li className="contacts-block__item" data-toggle="tooltip" data-placement="top" title="WhatsApp">
              <a href={`whatsapp://send?phone=${profile.phones.airtel}`}>
                <i className="fab fa-whatsapp mr-2"></i>WhatsApp
              </a>
            </li>
            <li className="contacts-block__item" data-toggle="tooltip" data-placement="top" title="Phone">
              <a href={`tel://${profile.phones.airtel}`}>
                <i className="fa fa-phone mr-2"></i>
                {`${profile.phones.airtel}`}
              </a>
            </li>
            <li className="contacts-block__item" data-toggle="tooltip" data-placement="top" title="Facebook">
              <a href="https://web.facebook.com/Clinton594">
                <i className="fab fa-facebook mr-2"></i> Facebook Profile
              </a>
            </li>
          </ul>
          <a className="btn" href={`mailto:${profile.email}`}>
            <i className="font-icon icon-envelope"></i> <small>Get in Touch [Email]</small>
          </a>
        </div>
      </div>
    </aside>
  );
}
