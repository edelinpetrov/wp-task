/* global window, document */
if (! window._babelPolyfill) {
    require('@babel/polyfill');
}

import React from 'react';
import ReactDOM from 'react-dom';
import Promo from './containers/Promo.jsx';

document.addEventListener('DOMContentLoaded', function() {
    ReactDOM.render(
        <Promo wpObject={window.task_object} />, document.getElementById('promo_root')
    );
});