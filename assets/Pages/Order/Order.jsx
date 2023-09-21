import {createRoot} from "react-dom/client";
import React, {useEffect, useState} from "react";
import Header from './components/Header';
import ProductCard from "./components/ProductCard";
import PayBotton from "./components/PayBotton";

const Order = () => {
    const [productos, setProductos] = useState([]);

    const [total,setTotal] = useState([]);

    //Hace peticion ajax para traer los productos
    const getProducts = ()=> {
        $.ajax({
            url:'/cartProducts',
            data:{
            }
        }).done(function (response) {
            let productos = response.productos || [];
            let total = response.total;
            setProductos(productos);
            setTotal(total)

            console.log(total)
        });
    }
    const instertOrder= (event) => {
        console.log("jsjsjs aun no hace nada ")
        event.preventDefault()
        window.open("/createOrder")
    }

    useEffect(() => {
        getProducts();
    }, [])

    return <div>
        <Header title={"Carrito"} />
        {
            productos.map((product) => {
                return <ProductCard key={product.id} producto={product} />
            })

        }
        <div style={{width:"100%", textAlign: "right"}}>
            <div style={{width:"100%", textAlign: "right"}}>Total : ${total}</div>
            <a href="">
                <button style={{background:"blue",
                    color: "white", width:"10%",
                    textAlign: "center"}}
                    onClick={instertOrder}>Buy</button>
            </a>

        </div>

    </div>
}

export default Order;


const root = createRoot(document.getElementById('root'));
root.render(<Order />);