Chat.Views.ChatView = Backbone.View.extend({
    el: '.chat-wrapper',
    events: {
        'click #send-msg': 'sendMessage',
        'keypress #chat-message': 'checkKey'
    },
    input: '#chat-message',
    initialize: function() {
        var self = this;
        Chat.vent.on('message:add', self.renderMessage, self);
        //event will be triggered when user was authorized in chat
        Chat.vent.on('user:setCurrent', function(user) {
            self.model = user;
        }, self);
        //event will be triggered when user was clicked in list
        Chat.vent.on('user:select', self.selectUser, self);
        Chat.vent.on('history:load', self.loadHistory, self);
    },
    renderMessage: function(message) {
        var msg = new Chat.Views.Message({model: message});
        var $container = this.$el.find('.chat-container');
        $container.append(msg.render().el);
        $container.animate({
            scrollTop: $container[0].scrollHeight
        }, 'slow');
        return this;
    },
    sendMessage: function() {
        var $input = this.$el.find(this.input);
        var msg = $input.val();
        $input.val('');
        if (msg) {
            //clear timestamp - it auto generated
            this.model.set('timestamp', '');
            this.model.set('message', msg);
            this.model.set('type', 'info');
            this.renderMessage(this.model);
            Chat.vent.trigger('message:send', msg);
        }
    },
    loadHistory: function(data) {
        for (var key in data) {
            var item = data[key];
            var user = {
                id: item.user_id, username: item.username, timestamp: item.timestamp,
                message: item.message, avatar_16: item.avatar_16, avatar_32: item.avatar_32,
                type: 'info'
            };
            this.renderMessage(new Chat.Models.User(user));
        }
    },
    checkKey: function(e) {
        //check if enter is pressed
        if (e.keyCode === 13) {
            this.sendMessage();
        }
    },
    selectUser: function(name) {
        if (this.model.get('username') != name) {
            this.$el.find(this.input).val('@' + name + ':');
        }
    }
});