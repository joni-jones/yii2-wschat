define([
    'jquery', 'backbone', 'underscore', 'collections/users',
    'models/user', 'jquery-cookie'
], function($, Backbone, _, Users, User) {
    var Chat = {};
    Chat.vent = _.extend({}, Backbone.Events);
    Chat.encode = function(input) {
        if (!input) {
            return '';
        }
        return input.replace(/[\u00A0-\u9999<>]/gim, function(i) {
            return '&#' + i.charCodeAt(0) + ';';
        });
    };
    Chat.dict = {
        'ru': {
            'New message from': 'Сообщение от',
            'Connect to chat': 'Подключился к чату',
            'Left this chat': 'Вышел из чата',
            'Send message': 'Отправить сообщение'
        }
    };
    Chat.t = function(message) {
        var lang = $.cookie('chatLang') || 'en';
        if (lang == 'en') {
            return message;
        }
        return Chat.dict[lang][message];
    };
    Chat.formatTime = function(time) {
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

    Chat.Room = function(options){
        this.conn = null;
        this.options = $.extend({
            url: location.host,
            port: 8080
        }, options);
        this.active = false;
        this.users = new Users();
        this.currentUser = null;
        this.init();
    };
    Chat.Room.prototype.init = function() {
        var self = this;
        try {
            self.conn = new WebSocket('ws://' + self.options.url + ':' + self.options.port);
            //set current chat room, by default - all
            self.cid = $('#chat-room-list .active').attr('data-chat');
            var timer = setInterval(function() {
                if (self.conn.readyState) {
                    self.auth();
                    clearInterval(timer);
                }
            }, 200);
        } catch (e) {
            console.log(e);
        }
        self.initLang();
        self.addConnectionHandlers();
        self.addEventsHandlers();
    };
    Chat.Room.prototype.initLang = function() {
        var lang = navigator.language || navigator.userLanguage;
        lang = lang.split('-');
        $.cookie('chatLang', lang[0], {expires: 1});
    };
    Chat.Room.prototype.addEventsHandlers = function(){
        var self = this;
        Chat.vent.on('user:auth', function(data){
            //if collection is empty - fill it
            if (!self.users.length) {
                self.fillUsers(data.users, data.user);
            }
            var user = new User(data.user);
            user.set('message', Chat.t('Connect to chat'));
            user.set('type', 'warning');
            self.users.add(user);
            //set current user
            if (!self.currentUser) {
                self.currentUser = user;
                Chat.vent.trigger('user:setCurrent', self.currentUser);
            }
            Chat.vent.trigger('message:add', user);
        });
        Chat.vent.on('message:send', function(msg) {
            self.currentUser.set('message', msg);
            self.sendMessage(self.currentUser);
        });
        Chat.vent.on('user:remove', function(data) {
            var user = self.users.get(data.id);
            user.set('message', Chat.t('Left this chat'));
            Chat.vent.trigger('message:add', user);
            self.users.remove(user);
        });
    };
    Chat.Room.prototype.addConnectionHandlers = function() {
        var self = this;
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
    Chat.Room.prototype.isFunction = function(name) {
        return typeof name === 'function';
    };
    Chat.Room.prototype.onMessage = function(e) {
        try {
            var response = JSON.parse(e.data);
            if (response.type == 'auth') {
                Chat.vent.trigger('user:auth', response.data);
            } else if(response.type == 'message') {
                var user = new User(response.data.message);
                user.set('type', 'info');
                //put copy of model to avoid message preparing
                this.showNotification(user.clone());
                Chat.vent.trigger('message:add', user);
            } else if(response.type == 'close') {
                Chat.vent.trigger('user:remove', response.data.user);
            }
        } catch (e) {
            console.log(e);
        }
    };
    Chat.Room.prototype.onClose = function(e) {
        console.log('close');
        console.log(e);
    };
    Chat.Room.prototype.sendMessage = function(data) {
        this.send({type: 'message', data: {message: data}});
    };
    Chat.Room.prototype.auth = function() {
        /**
         * @TODO need to create model with real user data, maybe on server
         * @TODO use simple json instead backbone model
         */
        var name = 'user #' + Math.floor(Math.random() * 100);
        var user = new User({name: name});
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
        Notification.requestPermission(function() {
            var title = Chat.t('New message from') + ' ' + user.get('name');
            var msg = user.get('message');
            if (msg.length > 40) {
                msg = msg.substring(0, 40) + '...';
            }
            var notification = new Notification(title, {
                icon: user.get('avatar_32'),
                body: msg,
                lang: self.lang
            });
            notification.onshow = function() {
                //hide notification after 5 secs
                setTimeout(function() {
                    notification.close();
                }, 5000);
            };
        });
    };

    return Chat;
});

