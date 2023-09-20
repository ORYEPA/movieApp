import React from "react";

const ProductCard = ({producto}) => {

    return <div>
        {producto.id} - {producto.tittle} - ${producto.price}
    </div>
}

export default ProductCard;
