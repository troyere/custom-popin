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
            configText: '',
            configSizeMode: '',
            configWidth: '',
            configHeight: ''
        };
    },

    getConfig: function() {
        return {
            title: this.state.configTitle,
            text: this.state.configText,
            sizeMode: this.state.configSizeMode,
            width: this.state.configWidth,
            height: this.state.configHeight
        };
    },

    setConfig: function(config) {
        this.setState({
            configTitle: config.title,
            configText: config.text,
            configSizeMode: config.sizeMode,
            configWidth: config.width,
            configHeight: config.height
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

    handleChangeSizeMode: function(event) {
        this.setState({ formDirty: true, configSizeMode: event.target.value });
        if (event.target.value !== 'custom') {
            this.setState({ configWidth: null, configHeight: null });
        }
    },

    handleChangeWidth: function(event) {
        this.setState({ formDirty: true, configWidth: event.target.value });
    },

    handleChangeHeight: function(event) {
        this.setState({ formDirty: true, configHeight: event.target.value });
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

        var fieldStyleWidth  = { display: 'none' };
        var fieldStyleHeight = { display: 'none' };
        if (this.state.configSizeMode === 'custom') {
            fieldStyleWidth.display  = '';
            fieldStyleHeight.display = '';
        }

        return (
            <form id="form-custom-popin">

                <div className="form-alert-container">
                    {messageType && message ? <AlertComponent type={messageType} message={message} /> : null}
                </div>

                <div className="form-group" id="field-title">
                    <label htmlFor="title" className="control-label col-sm2">
                        Title
                    </label>
                    <input type="text" placeholder="Title" name="title" id="title" className="form-control"
                           value={this.state.configTitle}
                           onChange={this.handleChangeTitle} />
                </div>

                <div className="form-group" id="field-text">
                    <label htmlFor="text" className="control-label col-sm2">
                        Text
                    </label>
                    <textarea placeholder="Text" name="text" className="form-control"
                              value={this.state.configText}
                              onChange={this.handleChangeText} />
                </div>

                <div className="form-group" id="field-size-mode">
                    <label className="control-label col-sm2">
                        Size mode
                    </label>
                    <div className="form-group">
                        <label className="radio-inline">
                            <input type="radio" name="sizeMode" value="normal"
                                   checked={this.state.configSizeMode === 'normal'}
                                   onChange={this.handleChangeSizeMode} /> normal
                        </label>
                        <label className="radio-inline">
                            <input type="radio" name="sizeMode" value="small"
                                   checked={this.state.configSizeMode === 'small'}
                                   onChange={this.handleChangeSizeMode} /> small
                        </label>
                        <label className="radio-inline">
                            <input type="radio" name="sizeMode" value="large"
                                   checked={this.state.configSizeMode === 'large'}
                                   onChange={this.handleChangeSizeMode} /> large
                        </label>
                        <label className="radio-inline">
                            <input type="radio" name="sizeMode" value="full-page"
                                   checked={this.state.configSizeMode === 'full-page'}
                                   onChange={this.handleChangeSizeMode} /> full-page
                        </label>
                        <label className="radio-inline">
                            <input type="radio" name="sizeMode" value="custom"
                                   checked={this.state.configSizeMode === 'custom'}
                                   onChange={this.handleChangeSizeMode} /> custom
                        </label>
                    </div>
                </div>

                <div className="form-group" id="field-width" style={fieldStyleWidth}>
                    <label htmlFor="width" className="control-label col-sm2">
                        Width
                    </label>
                    <input type="number" placeholder="Width" name="width" id="width" className="form-control"
                           value={this.state.configWidth}
                           onChange={this.handleChangeWidth} />
                </div>

                <div className="form-group" id="field-height" style={fieldStyleHeight}>
                    <label htmlFor="height" className="control-label col-sm2">
                        Height
                    </label>
                    <input type="number" placeholder="Height" name="height" id="height" className="form-control"
                           value={this.state.configHeight}
                           onChange={this.handleChangeHeight} />
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