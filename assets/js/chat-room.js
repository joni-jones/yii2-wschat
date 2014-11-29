Chat.Room = function (options) {
    this.conn = null;
    this.options = $.extend({
        url: location.host,
        port: 8080
    }, options);
    this.active = false;
    this.users = new Chat.Collections.Users();
    this.currentUser = null;
    this.init();
};
Chat.Room.prototype.init = function () {
    var self = this;
    try {
        self.cid = $('#chat-room-list .active').attr('data-chat');
        if (self.cid) {
            self.conn = new WebSocket('ws://' + self.options.url + ':' + self.options.port);
            self.addConnectionHandlers();
            //set current chat room, by default - all
            var timer = setInterval(function () {
                if (self.conn.readyState) {
                    self.auth();
                    clearInterval(timer);
                }
            }, 200);
        } else {
            Helper.Message.error('Current room is not available');
        }
    } catch (e) {
        console.log(e);
    }
    self.initLang();
    self.addEventsHandlers();
};
Chat.Room.prototype.initLang = function () {
    var lang = navigator.language || navigator.userLanguage;
    lang = lang.split('-');
    $.cookie('chatLang', lang[0], {expires: 1});
};
Chat.Room.prototype.addEventsHandlers = function () {
    var self = this;
    Chat.vent.on('user:auth', function (data) {
        //if collection is empty - fill it
        if (!self.users.length) {
            self.fillUsers(data.users, data.user);
        }
        var user = new Chat.Models.User(data.user);
        user.set('message', Helper.t('Connect to chat'));
        user.set('type', 'warning');
        self.users.add(user);
        //set current user
        if (!self.currentUser) {
            self.currentUser = user;
            Chat.vent.trigger('user:setCurrent', self.currentUser);
        }
        Chat.vent.trigger('message:add', user);
    });
    Chat.vent.on('message:send', function (msg) {
        self.currentUser.set('message', msg);
        self.sendMessage(self.currentUser);
    });
    Chat.vent.on('user:remove', function (data) {
        var user = self.users.get(data.id);
        user.set('message', Helper.t('Left this chat'));
        Chat.vent.trigger('message:add', user);
        self.users.remove(user);
    });
};
Chat.Room.prototype.addConnectionHandlers = function () {
    var self = this;
    self.conn.onclose = function (e) {
        if (!self.active) {
            return;
        }
        if (self.isFunction(self.onClose)) {
            self.onClose(e);
        }
    };
    self.conn.onerror = function (e) {
        console.log('error');
        self.conn.close();
    };
    self.conn.onmessage = function (e) {
        if (self.isFunction(self.onMessage)) {
            self.onMessage(e);
        }
    };

    $(window).unload(function () {
        self.conn.close();
    });
};
Chat.Room.prototype.isFunction = function (name) {
    return typeof name === 'function';
};
Chat.Room.prototype.onMessage = function (e) {
    try {
        var response = JSON.parse(e.data);
        if (response.type == 'auth') {
            Chat.vent.trigger('user:auth', response.data);
        } else if (response.type == 'message') {
            var user = new Chat.Models.User(response.data.message);
            user.set('type', 'info');
            //put copy of model to avoid message preparing
            this.showNotification(user.clone());
            Chat.vent.trigger('message:add', user);
        } else if (response.type == 'close') {
            Chat.vent.trigger('user:remove', response.data.user);
        }
    } catch (e) {
        console.log(e);
    }
};
Chat.Room.prototype.onClose = function (e) {
    console.log('close');
    console.log(e);
};
Chat.Room.prototype.sendMessage = function (data) {
    this.send({type: 'message', data: {message: data}});
};
Chat.Room.prototype.auth = function () {
    /**
     * @TODO need to create model with real user data, maybe on server
     * @TODO use simple json instead backbone model
     */
    var name = 'user #' + Math.floor(Math.random() * 100);
    var user = new Chat.Models.User({name: name});
    this.send({type: 'auth', data: {user: user.toJSON(), cid: this.cid}});
};
Chat.Room.prototype.send = function (request) {
    this.conn.send(JSON.stringify(request));
};
Chat.Room.prototype.fillUsers = function (users, user) {
    for (var key in users) {
        if (key !== user.id) {
            this.users.add(users[key]);
        }
    }
};
Chat.Room.prototype.showNotification = function (user) {
    var self = this;
    if (!('Notification' in window)) {
        return;
    }
    Notification.requestPermission(function () {
        var title = Helper.t('New message from') + ' ' + user.get('name');
        var msg = user.get('message');
        if (msg.length > 40) {
            msg = msg.substring(0, 40) + '...';
        }
        var notification = new Notification(title, {
            icon: user.get('avatar_32'),
            body: msg,
            lang: self.lang
        });
        notification.onshow = function () {
            //hide notification after 5 secs
            setTimeout(function () {
                notification.close();
            }, 5000);
        };
    });
};