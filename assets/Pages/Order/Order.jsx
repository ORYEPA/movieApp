import {createRoot} from "react-dom/client";
import React, {useEffect, useState} from "react";
import Header from './components/Header';
import ProductCard from "./components/ProductCard";

const Order = () => {
    const [productos, setProductos] = useState([]);

    //Hace peticion ajax para traer los productos
    const getProducts = () => {
          $.ajax({
              url: "/app/getCarrito",
              data: {},
              beforeSend: () => {},
              complete: () => {},
              success: (response) => {
                  let {productos, total} = response;
                  console.log({response});
                  setProductos(productos);
              },
              error: (xhr) => {
              }
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