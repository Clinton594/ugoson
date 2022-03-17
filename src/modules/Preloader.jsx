import React, { useEffect, useState } from "react";

export default function Preloader() {
  const [style, setStyle] = useState(0);
  useEffect(() => {
    const interval = setInterval(() => {
      if (style === 100) {
        clearInterval(interval);
      } else {
        setStyle((x) => x + 1);
      }
    }, 100);
  }, []);
  return (
    <div id="preloader" className="preloader">
      <div className="preloader__wrap">
        <div className="circle-pulse">
          <div className="circle-pulse__1"></div>
          <div className="circle-pulse__2"></div>
        </div>
        <div className="preloader__progress">
          <span style={{ width: `${style}%` }}></span>
        </div>
      </div>
    </div>
  );
}
