import {createRoot} from "react-dom/client";
import React, {useEffect, useState} from "react";
import Header from './components/Header';
import ProductCard from "./components/ProductCard";
import RenderStatus from "./components/RenderStatus";


const ChargeOrder = () => {
    const orderValue = $("#orderValue").val()
    const [productos, setProductos] = useState([]);
    const [status,setStatus] = useState({statusName: ''});
    const [allStatus,setAllStatus] = useState([]);

    //Hace peticion ajax para traer los productos

    const getOrder = ()=> {
        $.ajax({
            url:'/getOrder/'+orderValue,
            data:{
            }
        }).done(function (response) {
            let productos = response.productos || [];
            console.log(productos)
            let total = response.total;
            setProductos(productos);
            let status=response.status
            setStatus(status)
        });
    }
    const chargeStatus = () => {
        $.ajax({
            url:'/allStatus',
            data:{
            }
        }).done(function (response) {
            let status=response.status || [];
            setAllStatus(status)
        });
    }
    const doInterval= () => {
        const interval = setInterval(() => {
            getOrder();
        }, 1000);

    }
    const cancelar = () => {
        console.log({orderValue})
        $.ajax({
            url:'/cancelOrder/'+orderValue,
            data:{
            }
        })
        .done(function (response) {
            console.log(response)
        });

    }

    useEffect(() => {
        chargeStatus();
        doInterval();

    }, [])

    return <div>
        <Header title={"Orden "}

            />
        <div> {status.statusName}</div>
        <div className="w-full flex">
            <div className="w-9/12 max-h-screen overflow-y-auto">
                {
                    productos.map((product) => {
                        return <ProductCard key={product.id_product} producto={product} />
                    })

                }
            </div>
            <div className="w-3/12 max-h-screen overflow-y-auto">
                {
                    allStatus.map((sta)=>{
                        return <RenderStatus key={sta.id} status={sta} actualStatus={status} />
                    })
                }
                {
                    status.status_id <= 40 ?<div>
                        <button
                            style={{background:"blue",
                            color: "white", width:"70px",
                            textAlign: "center"}}
                            onClick={cancelar}>cancelar</button>
                    </div>: null
                }
            </div>
        </div>
    </div>
}

export default ChargeOrder;


const root = createRoot(document.getElementById('root'));
root.render(<ChargeOrder />);