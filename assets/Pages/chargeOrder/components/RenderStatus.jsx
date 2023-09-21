import React from "react";

const RenderStatus = ({status , actualStatus}) => {

    return actualStatus.status_id === 60 || actualStatus.status_id === 70 ? <div>
        {
            status.id === 60 || status === 70 ? <span>
                Status: {actualStatus.statusName}
            </span> : null

        }
    </div> : status.id < 60 ? <div className="border rounded flex">
        <div className="p-4 w-11/12 border-r flex flex-col">
            <div className="text-lg mb-4">{status.statusName}</div>
        </div>
        {
            actualStatus.status_id >= status.id ? <div>
                <div className="p-4 w-1/12 bg-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5}
                         stroke="currentColor" className="w-6 h-6">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
            </div> : <div>
                <div className="p-4 w-1/12 bg-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5}
                         stroke="currentColor" className="w-6 h-6">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        }

    </div> : null
}




export default RenderStatus;
