
module.exports = {

    createUrl: 'http://127.0.0.1:8000/modal-script/create',
    showUrl: 'http://127.0.0.1:8000/modal-script/download',

    create: function(config) {
        return $.ajax({
            url: this.createUrl,
            dataType: 'json',
            type: 'GET',
            data: { config: config },
            error: function(xhr, status, err) {
                console.error(this.url, status, err.toString());
            }.bind(this)
        });
    }

};