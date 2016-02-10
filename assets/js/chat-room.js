Chat.Room = function(options) {
    this.conn = null;
    this.options = $.extend({
        url: location.host,
        port: 8080,
        currentUserId: '',
        username: ''
    }, options);
    this.active = false;
    this.users = new Chat.Collections.Users();
    this.currentUser = null;
};
Chat.Room.prototype.init = function() {
    var self = this;
    try {
        self.cid = $('#chat-room-list .active').attr('data-chat');
        if (self.cid) {
            //if user id is not set - need to generate it(used for non auth users chat)
            if (!self.options.currentUserId) {
                self.options.currentUserId = Helper.uid();
                Cookies.set('chatUserId', self.options.currentUserId);
            }
            self.conn = new WebSocket('ws' + (location.protocol === 'https:' ? 's' : '') + '://'
                + self.options.url + ':' + self.options.port);
            self.addConnectionHandlers();
            //set current chat room, by default - all
            var timer = setInterval(function() {
                if (self.conn.readyState == 1) {
                    self.auth();
                    clearInterval(timer);
                }
            }, 200);
        } else {
            Helper.Message.error(Helper.t('Current room is not available'));
        }
    } catch (e) {
        Helper.Message.error(Helper.t('Connection error. Try to reload page'));
        console.log(e);
    }
    self.initLang();
    self.addEventsHandlers();
};
Chat.Room.prototype.initLang = function() {
    var lang = navigator.language || navigator.userLanguage;
    lang = lang.split('-');
    Cookies.set('chatLang', lang[0], {expires: 1});
};
Chat.Room.prototype.addEventsHandlers = function() {
    var self = this;
    Chat.vent.on('user:auth', function (data) {
        var user = new Chat.Models.User(data.user);
        //new user join to chat
        if (typeof data.join !== 'undefined' && data.join) {
            user.set('message', Helper.t('Connect to chat'));
            user.set('type', 'warning');
            Chat.vent.trigger('message:add', user);
        } else {
            //current user get auth response
            self.fillUsers(data.users, data.user);
            self.currentUser = user;
            Chat.vent.trigger('user:setCurrent', self.currentUser);
            Chat.vent.trigger('history:load', data.history);
        }
        self.users.add(user);
    });
    Chat.vent.on('message:send', function (msg) {
        self.currentUser.set('message', msg);
        self.sendMessage(self.currentUser);
    });
    Chat.vent.on('user:remove', function (data) {
        var user = self.users.get(data.id);
        user.set({
            message: Helper.t('Left this chat'),
            timestamp: ''
        });
        Chat.vent.trigger('message:add', user);
        self.users.remove(user);
    });
};
Chat.Room.prototype.addConnectionHandlers = function() {
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
        Helper.Message.error('Connection refused');
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
Chat.Room.prototype.isFunction = function(name) {
    return typeof name === 'function';
};
Chat.Room.prototype.onMessage = function(e) {
    try {
        var response = JSON.parse(e.data);
        switch (response.type) {
            case 'auth':
                Chat.vent.trigger('user:auth', response.data);
                break;
            case 'message':
                var user = new Chat.Models.User(response.data.message);
                user.set('type', 'info');
                //put copy of model to avoid message preparing
                this.showNotification(user.clone());
                Chat.vent.trigger('message:add', user);
                break;
            case 'close':
                Chat.vent.trigger('user:remove', response.data.user);
                break;
            case 'error':
                Helper.Message.error(Helper.t(response.data.message));
                Cookies.remove('chatUserId');
                break;
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
Chat.Room.prototype.auth = function() {
    var user = new Chat.Models.User({id: this.options.currentUserId, username: this.options.username});
    this.send({type: 'auth', data: {user: user.toJSON(), cid: this.cid}});
};
Chat.Room.prototype.send = function(request) {
    this.conn.send(JSON.stringify(request));
};
Chat.Room.prototype.fillUsers = function(users, user) {
    for (var key in users) {
        if (key !== user.id) {
            this.users.add(users[key]);
        }
    }
};
Chat.Room.prototype.showNotification = function(user) {
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
