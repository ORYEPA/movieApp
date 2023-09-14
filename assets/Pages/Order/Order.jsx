import {createRoot} from "react-dom/client";
import React, {useEffect, useState} from "react";
import Header from './components/Header';
import ProductCard from "./components/ProductCard";

const Order = () => {
    const [productos, setProductos] = useState([]);

    
    //Hace peticion ajax para traer los productos
    const getProducts = () => {
          return [{
            id: 1,
            tittle: "televison",
            price: 5
        },
            {
                id: 2,
                tittle: "Ipad",
                price: 50
            },
        ];
    }

    useEffect(() => {
        let response = getProducts();
        setProductos(response);

    }, [])

    return <div>
        <Header title={"Carrito"} />
        {
            productos.map((product) => {
                return <ProductCard producto={product} />
            })
        }
    </div>
}

export default Order;


const root = createRoot(document.getElementById('root'));
root.render(<Order />);