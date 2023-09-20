import {createRoot} from "react-dom/client";
import React, {useEffect, useState} from "react";
import Header from './components/Header';
import ProductCard from "./components/ProductCard";
import PayBotton from "./components/PayBotton";

const Order = () => {
    const [productos, setProductos] = useState([]);


    //Hace peticion ajax para traer los productos
    const getProducts = ()=> {
        $.ajax({
            url:'/cartProducts',
            data:{
            }
        }).done(function (response) {
            let productos = response.productos || [];
            setProductos(productos);
        });
    }

    useEffect(() => {
        getProducts();
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