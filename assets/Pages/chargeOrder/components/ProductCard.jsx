import React from "react";

const ProductCard = ({producto}) => {

    return <div className="border rounded flex ">
        <div className="w-3/12 border-r" style={{maxWidth: "230px"}}>
            <img className="w-full" src={producto.image} alt={producto.title}/>
        </div>
        <div className="p-4 w-8/12 border-r flex flex-col">
            <div className="text-lg mb-4">{producto.title}</div>
            <div className="text-xs">{producto.description}</div>
        </div>
        <div className="p-4 w-1/12 ">${producto.price}</div>
        <div className="p-4 w-1/12 ">Piezas : {producto.quantity}</div>


    </div>
}

export default ProductCard;
