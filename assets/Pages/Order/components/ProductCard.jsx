import React from "react";

const ProductCard = ({producto}) => {

    return <div className="border rounded flex w-full">
        <div className="p-4 w-3/12">{producto.id}</div>
        <div className="p-4 w-8/12 border-r">{producto.tittle}</div>
        <div className="p-4 w-1/12 border-r">${producto.price}</div>
    </div>
}

export default ProductCard;
