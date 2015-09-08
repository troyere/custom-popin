(function() {
    'use strict';

    var React = require('react'),
        ConfigFormComponent = require('./components/configFormComponent.jsx');

    React.render(<ConfigFormComponent />, document.getElementById('form-container'));

}());