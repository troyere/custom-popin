var React = require('react'),
    ConfigService = require('../services/configService.jsx'),
    ModalScriptService = require('../services/modalScriptService.jsx'),
    AlertComponent = require('./alertComponent.jsx');

var Form = React.createClass({

    getInitialState: function() {
        return {
            message: '',
            messageType: '',
            formDirty: false,
            configTitle: '',
            configText: ''
        };
    },

    getConfig: function() {
        return {
            title: this.state.configTitle,
            text: this.state.configText
        };
    },

    setConfig: function(config) {
        this.setState({
            configTitle: config.title,
            configText: config.text
        });
    },

    setMessage: function(type, message) {
        this.setState({
            messageType: type,
            message: message
        });
    },

    handleCreateScript: function(e) {
        e.preventDefault();
        var button = $(e.target).button('loading');
        ConfigService.save(this.getConfig()).done(function(response) {
            this.setState({ formDirty: false });
            button.button('reset');
            if (response.errors) {
                this.setMessage('danger', response.errors);
            } else {
                this.setMessage('success', 'The modal script has been successfully saved.');
            }
        }.bind(this));
    },

    handleShowScript: function() {
        window.open(ModalScriptService.showUrl, '_blank');
    },

    handleChangeTitle: function(event) {
        this.setState({ formDirty: true, configTitle: event.target.value });
    },

    handleChangeText: function(event) {
        this.setState({ formDirty: true, configText: event.target.value });
    },

    componentDidMount: function() {
        // Fill the form
        ConfigService.find().done(function(response) {
            if (response.config) {
                this.setConfig(response.config);
            }
        }.bind(this));

        // Auto save when idle
        var currentForm = $('#form-custom-popin');
        currentForm.idleTimer(2000);
        currentForm.on('idle.idleTimer', function() {
            if (this.state.formDirty) {
                ConfigService.save(this.getConfig()).done(function(response) {
                    this.setState({ formDirty: false });
                    if (response.errors) {
                        this.setMessage('danger', response.errors);
                    } else {
                        this.setMessage('info', 'The modal script has been automatically saved.');
                    }
                }.bind(this));
            }
        }.bind(this));
    },

    render: function() {
        var message     = this.state.message;
        var messageType = this.state.messageType;

        var title = this.state.configTitle;
        var text  = this.state.configText;

        return (
            <form id="form-custom-popin" className="form-horizontal">

                <div className="row form-alert-container">
                    {messageType && message ? <AlertComponent type={messageType} message={message} /> : null}
                </div>

                <div className="form-group">
                    <label htmlFor="title" className="control-label col-sm2">
                        Title
                    </label>
                    <input type="text" placeholder="Title" name="title" id="title" className="form-control"
                           value={title} onChange={this.handleChangeTitle} />
                </div>

                <div className="form-group">
                    <label htmlFor="text" className="control-label col-sm2">
                        Text
                    </label>
                    <textarea placeholder="Text" name="text" className="form-control"
                              value={text} onChange={this.handleChangeText} />
                </div>

                <div className="form-group">
                    <button type="button" className="btn btn-default"
                            data-loading-text="Saving..."
                            onClick={this.handleCreateScript}>
                        Save
                    </button>
                    <button type="button" className="btn btn-default pull-right"
                            onClick={this.handleShowScript}>
                        Show
                    </button>
                </div>

            </form>
        );
    }

});

module.exports = Form;