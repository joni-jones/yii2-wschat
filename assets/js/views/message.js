define([
    'underscore', 'backbone', 'chat', 'models/user'
], function(_, Backbone, Chat, User) {
    var Message = Backbone.View.extend({
        model: User,
        template: '#msg-tpl',
        render: function() {
            var time = new Date();
            var msg = Chat.makeLink(Chat.encode(this.model.get('message')));
            this.model.set('timestamp', Chat.formatTime(time));
            this.model.set('message', msg);
            if (!this.model.get('type')) {
                this.model.set('type', 'warning');
            }
            var template = _.template($(this.template).html());
            this.$el.html(template(this.model.toJSON()));
            return this;
        }
    });
    return Message;
});