define([
    'underscore', 'backbone', 'chat', 'views/message'
], function(_, Backbone, Chat, Message) {
    var ChatView = Backbone.View.extend({
        el: '.chat-wrapper',
        events: {
            'click #send-msg': 'sendMessage',
            'keypress #chat-message': 'checkKey'
        },
        initialize: function() {
            var self = this;
            Chat.vent.on('message:add', this.renderMessage, self);
            Chat.vent.on('user:setCurrent', function(user) {
                self.model = user;
            }, self);
        },
        renderMessage: function(message) {
            message.set('message', Chat.encode(message.get('message')));
            if (!message.get('type')) {
                message.set('type', 'warning');
            }
            var msg = new Message({model: message});
            var $container = this.$el.find('.chat-container');
            $container.append(msg.render().el);
            $container.animate({
                scrollTop: $container[0].scrollHeight
            }, 'slow');
            return this;
        },
        sendMessage: function() {
            var $input = $('[name="chat_message"]');
            var msg = $input.val();
            $input.val('');
            if (msg) {
                this.model.set('message', msg);
                this.model.set('type', 'info');
                this.renderMessage(this.model);
                Chat.vent.trigger('message:send', msg);
            }
        },
        checkKey: function(e) {
            //check if enter is pressed
            if (e.keyCode === 13) {
                this.sendMessage();
            }
        }
    });
    return ChatView;
});