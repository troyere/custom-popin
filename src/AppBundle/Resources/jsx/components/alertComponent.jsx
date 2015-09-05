var React = require('react');

var Alert = React.createClass({

    render: function() {
        var type    = this.props.type;
        var message = this.props.message;
        var className = 'alert alert-' + type;
        var title = '';
        switch (type) {
            case 'success': title = 'Well done!'; break;
            case 'danger':  title = 'Oh snap!';   break;
            case 'warning': title = 'Warning!';   break;
            case 'info':    title = 'Heads up!';  break;
        }
        return (
            <div className={className} role="alert">
                <strong>{title}</strong> {message}
            </div>
        );
    }

});

module.exports = Alert;