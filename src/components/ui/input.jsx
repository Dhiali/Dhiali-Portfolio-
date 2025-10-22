import * as React from "react";

import { cn } from "./utils";

function Input({ className, type, ...props }) {
  return (<input
      type={type}
      data-slot="input"
      className={cn(
        "file)}
      {...props}
    />
  );
}

export { Input };


