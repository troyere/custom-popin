var React = require('react');

var Modal = React.createClass({

    getInitialState: function() {
        return {
            author: '',
            text: ''
        };
    },

    ajaxLoad: function() {
        return $.ajax({
            url: this.props.url,
            dataType: 'json',
            cache: false,
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },

    componentDidMount: function() {
        this.ajaxLoad().done(function(response) {
            if (response.config) {
                this.setState({
                    author: response.config.author,
                    text: response.config.text
                });
            }
        }.bind(this));
    },

    render: function() {
        return (
            <div>
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                    Launch demo modal
                </button>
            </div>
        );
    }

});

module.exports = Form;