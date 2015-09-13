(function() {
    'use strict';

    var React          = require('react'),
        ConfigService  = require('../services/configService.jsx'),
        ScriptService  = require('../services/scriptService.jsx'),
        AlertComponent = require('./alertComponent.jsx');

    module.exports = React.createClass({

        getInitialState: function () {
            return {
                message: null,
                messageType: null,
                formDirty: false,
                configTitle: '',
                configImage: null,
                configText: '',
                configSizeMode: 'normal',
                configContentBehavior: 'adaptive-height',
                configWidth: null,
                configHeight: null,
                configTheme: 'none'
            };
        },

        getConfig: function () {
            return {
                title: this.state.configTitle,
                image: this.state.configImage,
                text: this.state.configText,
                sizeMode: this.state.configSizeMode,
                contentBehavior: this.state.configContentBehavior,
                width: this.state.configWidth,
                height: this.state.configHeight,
                theme: this.state.configTheme
            };
        },

        setConfig: function (config) {
            this.setState({
                configTitle: config.title,
                configImage: config.image,
                configText: config.text,
                configSizeMode: config.sizeMode,
                configContentBehavior: config.contentBehavior,
                configWidth: config.width,
                configHeight: config.height,
                configTheme: config.theme
            });
        },

        setMessage: function (type, message) {
            this.setState({ messageType: type, message: message });
        },

        clearMessage: function () {
            this.setState({ messageType: '', message: '' });
        },

        handleSave: function (e) {
            e.preventDefault();
            var button = $(e.target).button('loading');
            ConfigService.save(this.getConfig()).done(function (response) {
                this.setState({ formDirty: false });
                button.button('reset');
                if (response.errors) {
                    this.setMessage('danger', response.errors);
                } else {
                    this.setMessage('success', 'The script has been successfully saved.');
                    ScriptService.show();
                }
            }.bind(this));
        },

        handleChangeTitle: function (event) {
            this.setState({ formDirty: true, configTitle: event.target.value });
        },

        handleChangeImage: function (event) {
            var fileReader = new FileReader();
            var file       = event.target.files[0];
            var maxSize    = 2000000;

            fileReader.onload = function (upload) {
                if (upload.total > maxSize) {
                    this.setMessage('danger', 'The image is too large (2mb max).');
                    this.setState({ formDirty: false });
                } else {
                    this.clearMessage();
                    this.setState({ formDirty: true, configImage: upload.target.result });
                }
            }.bind(this);
            fileReader.readAsDataURL(file);
        },

        handleChangeText: function (event) {
            this.setState({ formDirty: true, configText: event.target.value });
        },

        handleChangeSizeMode: function (event) {
            this.setState({ formDirty: true, configSizeMode: event.target.value });
            if (event.target.value !== 'custom') {
                this.setState({ configWidth: null, configHeight: null });
            }
        },

        handleChangeWidth: function (event) {
            this.setState({ formDirty: true, configWidth: event.target.value });
        },

        handleChangeHeight: function (event) {
            this.setState({ formDirty: true, configHeight: event.target.value });
        },

        handleChangeContentBehavior: function (event) {
            this.setState({ formDirty: true, configContentBehavior: event.target.value });
        },

        handleChangeTheme: function (event) {
            this.setState({ formDirty: true, configTheme: event.target.value });
        },

        componentDidMount: function () {
            // Fill the form
            ConfigService.find().done(function (response) {
                if (response.config) {
                    console.log(response.config);
                    this.setConfig(response.config);
                }
            }.bind(this));

            // Auto save when idle
            var currentForm = $('#form-custom-popin');
            currentForm.idleTimer(2000);
            currentForm.on('idle.idleTimer', function () {
                if (this.state.formDirty) {
                    ConfigService.save(this.getConfig()).done(function (response) {
                        this.setState({ formDirty: false });
                        if (response.errors) {
                            this.setMessage('danger', response.errors);
                        } else {
                            this.setMessage('info', 'The script has been automatically saved.');
                        }
                    }.bind(this));
                }
            }.bind(this));
        },

        render: function () {
            var message     = this.state.message;
            var messageType = this.state.messageType;

            var styleFieldWidth  = { display: 'none' };
            var styleFieldHeight = { display: 'none' };

            if (this.state.configSizeMode === 'custom') {
                styleFieldWidth.display  = '';
                styleFieldHeight.display = '';
            }

            return (
                <form id="form-custom-popin" encType="multipart/form-data">

                    <div className="form-alert-container">
                        {messageType && message ? <AlertComponent type={messageType} message={message}/> : null}
                    </div>

                    <div className="form-group">
                        <label htmlFor="title" className="control-label col-sm2">
                            Title
                        </label>
                        <input type="text" placeholder="Title" name="title" id="title" className="form-control"
                               value={this.state.configTitle}
                               onChange={this.handleChangeTitle}/>
                    </div>

                    <div className="form-group">
                        <label htmlFor="text" className="control-label col-sm2">
                            Text
                        </label>
                        <textarea placeholder="Text" name="text" className="form-control"
                                  value={this.state.configText}
                                  onChange={this.handleChangeText}/>
                    </div>

                    <div className="form-group">
                        <label htmlFor="title-icon" className="control-label col-sm2">
                            Image
                        </label>
                        <input type="file" name="title-icon" id="title-icon"
                               onChange={this.handleChangeImage}/>
                    </div>

                    <div className="form-group">
                        <label className="control-label col-sm2">
                            Size mode
                        </label>
                        <div className="form-group">
                            <label className="radio-inline">
                                <input type="radio" name="sizeMode" value="normal"
                                       checked={this.state.configSizeMode === 'normal'}
                                       onChange={this.handleChangeSizeMode}/>
                                Normal
                            </label>
                            <label className="radio-inline">
                                <input type="radio" name="sizeMode" value="small"
                                       checked={this.state.configSizeMode === 'small'}
                                       onChange={this.handleChangeSizeMode}/>
                                Small
                            </label>
                            <label className="radio-inline">
                                <input type="radio" name="sizeMode" value="large"
                                       checked={this.state.configSizeMode === 'large'}
                                       onChange={this.handleChangeSizeMode}/>
                                Large
                            </label>
                            <label className="radio-inline">
                                <input type="radio" name="sizeMode" value="full-page"
                                       checked={this.state.configSizeMode === 'full-page'}
                                       onChange={this.handleChangeSizeMode}/>
                                Full-page
                            </label>
                            <label className="radio-inline">
                                <input type="radio" name="sizeMode" value="custom"
                                       checked={this.state.configSizeMode === 'custom'}
                                       onChange={this.handleChangeSizeMode}/>
                                Custom
                            </label>
                        </div>
                    </div>

                    <div className="form-group" style={styleFieldWidth}>
                        <label htmlFor="width" className="control-label col-sm2">
                            Width
                        </label>
                        <input type="number" placeholder="Width" name="width" id="width" className="form-control"
                               value={this.state.configWidth}
                               onChange={this.handleChangeWidth}/>
                    </div>

                    <div className="form-group" style={styleFieldHeight}>
                        <label htmlFor="height" className="control-label col-sm2">
                            Height
                        </label>
                        <input type="number" placeholder="Height" name="height" id="height" className="form-control"
                               value={this.state.configHeight}
                               onChange={this.handleChangeHeight}/>
                    </div>

                    <div className="form-group">
                        <label className="control-label col-sm2">
                            Content behavior
                        </label>
                        <div className="form-group">
                            <label className="radio-inline">
                                <input type="radio" name="contentBehavior" value="none"
                                       checked={this.state.configContentBehavior === 'none'}
                                       onChange={this.handleChangeContentBehavior}/>
                                None
                            </label>
                            <label className="radio-inline">
                                <input type="radio" name="contentBehavior" value="adaptive-height"
                                       checked={this.state.configContentBehavior === 'adaptive-height'}
                                       onChange={this.handleChangeContentBehavior}/>
                                Adaptive height
                            </label>
                            <label className="radio-inline">
                                <input type="radio" name="contentBehavior" value="vertical-scroll"
                                       checked={this.state.configContentBehavior === 'vertical-scroll'}
                                       onChange={this.handleChangeContentBehavior}/>
                                Vertical Scroll
                            </label>
                        </div>
                    </div>

                    <div className="form-group">
                        <label htmlFor="theme" className="control-label col-sm2">
                            Theme
                        </label>
                        <select name="theme" id="theme" className="form-control"
                                value={this.state.configTheme}
                                onChange={this.handleChangeTheme}>
                            <option value="none">None</option>
                            <option value="success">Success</option>
                            <option value="info">Info</option>
                            <option value="warning">Warning</option>
                            <option value="danger">Danger</option>
                        </select>
                    </div>

                    <div className="form-group">
                        <button type="button" id="button-save" className="btn btn-default"
                                data-loading-text="Saving..."
                                onClick={this.handleSave}>
                            Save
                        </button>
                    </div>

                </form>
            );
        }

    });

}());