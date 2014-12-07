(function() {
    window.Chat = {
        Models: {},
        Collections: {},
        Views: {}
    };
    Chat.vent = _.extend({}, Backbone.Events);
    Chat.encode = function(input) {
        if (!input) {
            return '';
        }
        return input.replace(/[\u00A0-\u9999<>]/gim, function(i) {
            return '&#' + i.charCodeAt(0) + ';';
        });
    };
    Chat.formatTime = function(secs) {
        var time = (secs) ? new Date(secs * 1000) : new Date();
        var pad = '00';
        var hours = '' + time.getHours();
        var mins = '' + time.getMinutes();
        return pad.substring(0, pad.length - hours.length) + hours + ':' +
            pad.substring(0, pad.length - mins.length) + mins;
    };
    Chat.makeLink = function(msg) {
        msg = msg.split(' ');
        for (var key in msg) {
            if (/^https?:\/\//.test(msg[key])) {
                msg[key] = '<a href="' + msg[key] + '" target="_blank">' + msg[key] + '</a>';
            }
        }
        return msg.join(' ').trim();
    };
}());

