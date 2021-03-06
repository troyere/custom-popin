(function() {
    'use strict';

    var React = require('react');

    module.exports = React.createClass({

        getTitle: function () {
            var title = '';
            switch (this.props.type) {
                case 'success':
                    title = 'Well done!';
                    break;
                case 'danger':
                    title = 'Oh snap!';
                    break;
                case 'warning':
                    title = 'Warning!';
                    break;
                case 'info':
                    title = 'Heads up!';
                    break;
            }
            return title;
        },

        getContent: function () {
            if (typeof this.props.message === 'string') {
                return this.props.message;
            } else {
                if (_.size(this.props.message) === 1) {
                    return _.first(this.props.message);
                }
                var rows = [];
                _.each(this.props.message, function (message, i) {
                    rows.push(<li key={i}>{message}</li>);
                });
                return <ul>{rows}</ul>;
            }
        },

        render: function () {
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

}());