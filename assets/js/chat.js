function Chat(options){
    this.conn = null;
    this.options = $.extend({
        url: location.host,
        port: 8080
    }, options);
    this.active = false;
    this.init();
}
Chat.prototype.init = function() {
    try {
        this.conn = new WebSocket('ws://' + this.options.url + ':' + this.options.port);
        this.active = true;
    } catch (e) {
        console.log(e);
    }
    this.addConnectionHandlers();
};
Chat.prototype.addConnectionHandlers = function() {
    var self = this;
    self.conn.onmessage = function(e) {
        if (self.isFunction(self.onOpen)) {
            self.onOpen(e);
        }
    };
    self.conn.onclose = function(e) {
        if (!self.active) {
            return;
        }
        if (self.isFunction(self.onClose)) {
            self.onClose(e);
        }
    };
    self.conn.onerror = function(e) {
        console.log('error');
        self.conn.close();
    };
    self.conn.onmessage = function(e) {
        if (self.isFunction(self.onMessage)) {
            self.onMessage(e);
        }
    };

    $(window).unload(function() {
        self.conn.close();
    });
};
Chat.prototype.isFunction = function(name) {
    return typeof name === 'function';
};
Chat.prototype.onOpen = function(e) {
    console.log('open');
    console.log(e);
};
Chat.prototype.onMessage = function(e) {
    console.log('message');
    console.log(e.data);
};
Chat.prototype.onClose = function(e) {
    console.log('close');
    console.log(e);
};
Chat.prototype.send = function(data) {
    if (this.conn.readyState == 1) {
        this.conn.send(data);
    }
};