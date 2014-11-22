(function(){
    window.Chat = {
        Models: {},
        Collections: {},
        Views: {}
    };
    Chat.vent = _.extend({}, Backbone.Events);
}());

Chat.Room = function(options){
    this.conn = null;
    this.options = $.extend({
        url: location.host,
        port: 8080
    }, options);
    this.active = false;
    this.init();
    this.users = new Chat.Collections.Users();
    this.currentUser = null;
};
Chat.Room.prototype.init = function() {
    var self = this;
    try {
        self.conn = new WebSocket('ws://' + self.options.url + ':' + self.options.port);
        //set current chat room, by default - all
        self.cid = $('.chat-rooms .active').attr('data-chat');
        var timer = setInterval(function() {
            if (self.conn.readyState) {
                self.auth();
                clearInterval(timer);
            }
        }, 200);
    } catch (e) {
        console.log(e);
    }
    self.addConnectionHandlers();
    self.addEventsHandlers();
};
Chat.Room.prototype.addEventsHandlers = function(){
    var self = this;
    Chat.vent.on('user:auth', function(data){
        //if collection is empty - fill it
        if (!self.users.length) {
            self.fillUsers(data.users, data.user);
        }
        var user = new Chat.Models.User(data.user);
        /**
         * @TODO create localization message
         */
        user.set('message', user.get('name') + ' connected to chat');
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
        console.log(response);
        if (response.type == 'auth') {
            Chat.vent.trigger('user:auth', response.data);
        } else if(response.type == 'message') {
            Chat.vent.trigger('message:add', new Chat.Models.User(response.data.message));
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
     */
    var name = 'user #' + Math.floor(Math.random() * 100);
    var user = new Chat.Models.User({name: name});
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

Chat.Models.User = Backbone.Model.extend({
    defaults: {
        name: '', avatar_16: 'assets/img/user_16.png', avatar_32: 'assets/img/user_32.png', message: ''
    }
});
Chat.Collections.Users = Backbone.Collection.extend({
    model: Chat.Models.User
});
Chat.Views.Message = Backbone.View.extend({
    model: Chat.Models.User,
    template: '#msg-tpl',
    render: function() {
        var template = _.template($(this.template).html());
        this.$el.html(template(this.model.toJSON()));
        return this;
    }
});
Chat.Views.Chat = Backbone.View.extend({
    el: '.chat-wrapper',
    events: {
        'click #send-msg': 'sendMessage'
    },
    initialize: function() {
        var self = this;
        Chat.vent.on('message:add', this.renderMessage, self);
        Chat.vent.on('user:setCurrent', function(user) {
            self.model = user;
        }, self);
    },
    renderMessage: function(message) {
        var msg = new Chat.Views.Message({model: message});
        this.$el.find('.chat-container').append(msg.render().el);
        return this;
    },
    sendMessage: function() {
        var msg = $('[name="chat_message"]').val();
        if (msg) {
            this.model.set('message', msg);
            this.renderMessage(this.model);
            Chat.vent.trigger('message:send', msg);
        }
    }
});
Chat.Views.User = Backbone.View.extend({
    model: Chat.Models.User,
    template: '#user-tpl',
    tagName: 'a',
    className: 'list-group-item',
    initialize: function() {
        this.model.on('remove', this.remove, this);
    },
    render: function() {
        var template = _.template($(this.template).html());
        this.$el.html(template(this.model.toJSON()));
        return this;
    },
    remove: function() {
        this.$el.remove();
    }
});
Chat.Views.UserList = Backbone.View.extend({
    collection: Chat.Collections.Users,
    el: '.list-group',
    initialize: function() {
        this.collection.on('add', this.renderUser, this);
    },
    renderUser: function(user) {
        var userView = new Chat.Views.User({model: user});
        this.$el.append(userView.render().el);
        return this;
    }
});