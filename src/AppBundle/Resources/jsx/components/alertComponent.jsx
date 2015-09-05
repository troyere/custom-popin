var React = require('react');

var Alert = React.createClass({

    getTitle: function() {
        var title = '';
        switch (this.props.type) {
            case 'success': title = 'Well done!'; break;
            case 'danger':  title = 'Oh snap!';   break;
            case 'warning': title = 'Warning!';   break;
            case 'info':    title = 'Heads up!';  break;
        }
        return title;
    },

    getContent: function() {
        if (typeof this.props.message === 'string') {
            return this.props.message
        } else {
            var rows = [];
            _.each(this.props.message, function(message) {
                rows.push(<li>{message}</li>);
            });
            return <ul>{rows}</ul>;
        }
    },

    render: function() {
        var className = 'alert alert-' + this.props.type;
        var title     = this.getTitle();
        var content   = this.getContent();

        return (
            <div className={className} role="alert">
                <strong>{title}</strong> {content}
            </div>
        );
    }

});

module.exports = Alert;