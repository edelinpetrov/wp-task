import React, { Component } from 'react';
import PropTypes from 'prop-types';

import fetchWP from '../utils/fetchWP';

export default class Admin extends Component {
    constructor(props){
        super(props);

        this.state = {
            products: [],
        }

        this.fetchWP = new fetchWP({
            restURL: this.props.wpObject.api_url,
            restNonce: this.props.wpObject.api_nonce,
        });

        this.updateInput = this.updateInput.bind(this);
    }

    componentDidMount(){
        this.fetchWP.get( 'task_product' )
            .then(products => this.setState((prevState, props) => {
                console.log(products);
                    return { products: products.map(this.mapProduct)};
                }),
                (err) => console.log( 'error', err )
            );
    }

    mapProduct(product){
        return {
            id: product.id,
            price: product.product_metadata.product_price,
            quantity: product.product_metadata.product_quantity,
            stock: product.product_metadata.product_stock,
            promo_start: product.product_metadata.product_promo_start,
            promo_end: product.product_metadata.product_promo_end,
            promo_price: product.product_metadata.product_promo_price,
            title: product.title.rendered
        }
    }

    updateSetting = () => {
        this.fetchWP.put( 'task_product', { promo_products: this.state.products } )
            .then(
                (json) => this.processOkResponse(json, 'saved'),
                (err) => console.log('error', err)
            );
    }

    processOkResponse = (json, action) => {
        if (json.success) {
            console.log(`Setting was ${action}.`, json);
        } else {
            console.log(`Setting was not ${action}.`, json);
        }
    }

    updateInput = (id, event) => {
        let products = this.state.products;

        for (let i=0; i <= products.length; i++) {
            if (products[i].id === id) {
                products[i][event.target.name] = event.target.value;
                this.setState({
                    products: products
                });
                break;
            }
        }

        this.updateSetting();
    }

    render() {
        const products = this.state.products.map((product, index) => {
            return (
                <tr key={index}>
                    <td>{product.title}</td>
                    <td>{product.price}</td>
                    <td>{product.quantity}</td>
                    <td>{product.stock}</td>
                    <td><input type="text" name='promo_start' onBlur={this.updateInput.bind(this, product.id)} defaultValue={product.promo_start}/></td>
                    <td><input type="text" name='promo_end' onBlur={this.updateInput.bind(this, product.id)} defaultValue={product.promo_end}/></td>
                    <td><input type="text" name='promo_price' onBlur={this.updateInput.bind(this, product.id)} defaultValue={product.promo_price}/></td>
                </tr>
            );
        });

        return (
            <div className="wrap">
                <h1>Mass Promotions</h1>
                <table className="widefat">
                    <thead>
                        <tr>
                            <th><strong>Name</strong></th>
                            <th><strong>Price</strong></th>
                            <th><strong>Quantity</strong></th>
                            <th><strong>Stock</strong></th>
                            <th><strong>Promo Start</strong></th>
                            <th><strong>Promo End</strong></th>
                            <th><strong>Promo Price</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        {products}
                    </tbody>
                </table>
            </div>
        );
    }
}

Admin.propTypes = {
    wpObject: PropTypes.object
};