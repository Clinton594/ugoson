import React, { useEffect, useState } from 'react'

export default function Preloader() {
  const [style, setStyle] = useState({width:0});
  useEffect(()=>{
    const interval = setInterval(()=>{
      if(style.width === 100){
        clearInterval(interval);
      }else{
        setStyle({...style, width: style.width + 1});
      }
    }, 100);
  }, []);
  return (
    <div id='preloader' className="preloader">
      <div className="preloader__wrap">
        <div className="circle-pulse">
          <div className="circle-pulse__1"></div>
          <div className="circle-pulse__2"></div>
        </div>
        <div className="preloader__progress"><span style={{width:`${style.width}%`}}></span></div>
      </div>
    </div>
  )
}
