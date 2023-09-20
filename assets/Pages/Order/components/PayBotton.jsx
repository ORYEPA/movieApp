import {createRoot} from "react-dom/client";
import React from "react";


function PayBotton({event}) {

    return <button
        style={{
            width: "200px"
        }}
        onClick={event}>pay</button>
}

export default PayBotton;