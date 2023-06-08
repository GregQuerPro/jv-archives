import React, { useState, useEffect } from "react";

export default function Component({label, message}) {
  const [isActive, setIsActive] = useState(true);

  useEffect(() => {
    const timer = setTimeout(() => {
      setIsActive(false);
    }, 200000);

    return () => clearTimeout(timer);
  }, []);

  return (
    <>
      <div className={`flash alert alert-${label} ${isActive ? "" : "desactivate"}`}>
        {message}
      </div>
    </>
  );
}
