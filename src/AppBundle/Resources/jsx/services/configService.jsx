
module.exports = {

    url: 'http://127.0.0.1:8000/configs',

    save: function(newConfig) {
        return $.ajax({
            url: this.url,
            dataType: 'json',
            type: 'POST',
            data: { config: newConfig },
            error: function(xhr, status, err) {
                console.error(this.url, status, err.toString());
            }.bind(this)
        });
    },

    find: function() {
        return $.ajax({
            url: this.url,
            dataType: 'json',
            cache: false,
            error: function(xhr, status, err) {
                console.error(this.url, status, err.toString());
            }.bind(this)
        });
    }

};