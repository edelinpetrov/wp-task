import React, { Component } from 'react';
import PropTypes from 'prop-types';
import fetchWP from '../utils/fetchWP';

export default class Promo extends Component {
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
            price: product.product_price,
            quantity: product.product_quantity,
            stock: product.product_stock,
            promo_start: product.product_promo_start,
            promo_end: product.product_promo_end,
            promo_price: product.product_promo_price,
            title: product.title.rendered
        }
    }

    updateSetting = (product) => {
        this.fetchWP.post( 'promo', { promo_product: product } )
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
        let name = event.target.name;
        let value = event.target.value;

        for (let i=0; i <= products.length; i++) {
            if (products[i].id === id) {
                products[i][name] = value;
                this.setState({
                    products: products
                });

                this.updateSetting(products[i]);
                break;
            }
        }
    }

    render() {
        const products = this.state.products.map((product, index) => {
            return (
                <tr key={index}>
                    <td>{product.title}</td>
                    <td>{product.price}</td>
                    <td>{product.quantity}</td>
                    <td>{product.stock}</td>
                    <td><input type="datetime-local" name='promo_start' onBlur={this.updateInput.bind(this, product.id)} defaultValue={product.promo_start}/></td>
                    <td><input type="datetime-local" name='promo_end' onBlur={this.updateInput.bind(this, product.id)} defaultValue={product.promo_end}/></td>
                    <td><input type="number" name='promo_price' onBlur={this.updateInput.bind(this, product.id)} defaultValue={product.promo_price}/></td>
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

// type check
Promo.propTypes = {
    wpObject: PropTypes.object
};