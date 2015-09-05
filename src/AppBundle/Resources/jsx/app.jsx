var React = require('react'),
    Form = require('./form.jsx');

React.render(
    <Form url="http://localhost:8000/configs" />,
    document.getElementById('form')
);
