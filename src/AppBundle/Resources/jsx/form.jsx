var React = require('react');

var Form = React.createClass({

    getInitialState: function() {
        return {
            submitted: false,
            configAuthor: '',
            configText: ''
        };
    },

    ajaxSubmit: function(newConfig) {
        return $.ajax({
            url: this.props.url,
            dataType: 'json',
            type: 'POST',
            data: { config: newConfig },
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
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

    handleSubmit: function(e) {
        e.preventDefault();
        this.ajaxSubmit({
            author: this.state.config.author,
            text: this.state.config.text
        });
        return;
    },

    componentDidMount: function() {
        this.ajaxLoad().done(function(response) {
            if (response.config) {
                this.setState({
                    config: {
                        author: response.config.author,
                        text: response.config.text
                    }
                });
            }
        }.bind(this));
    },

    handleChangeAuthor: function(event) {
        this.setState({ config: { author: event.target.value } });
        console.log(this.state);
    },

    handleChangeText: function(event) {
        this.setState({ config: { text: event.target.value } });
        console.log(this.state);
    },

    handleCreateScript: function() {

    },

    handleExecuteScript: function() {

    },

    render: function() {
        var author = this.state.author;
        var text   = this.state.text;

        return (
            <form className="form-custom-popin form-horizontal" onSubmit={this.handleSubmit}>

                <div className="form-group">
                    <label htmlFor="author" className="control-label col-sm2">
                        Author
                    </label>
                    <input type="text" placeholder="Author" id="author" className="form-control"
                           value={author} onChange={this.handleChangeAuthor} />
                </div>

                <div className="form-group">
                    <label htmlFor="text" className="control-label col-sm2">
                        Text
                    </label>
                    <input type="text" placeholder="Text" id="text" className="form-control"
                           value={text} onChange={this.handleChangeText} />
                </div>

                <div className="form-group">
                    <button type="submit" className="btn btn-default">
                        Save
                    </button>
                    <button type="button" className="btn btn-default"
                            onClick={this.handleCreateScript}>
                        Create script
                    </button>
                    <button type="button" className="btn btn-default"
                            onClick={this.handleExecuteScript}>
                        Execute script
                    </button>
                </div>

            </form>
        );
    }

});

module.exports = Form;