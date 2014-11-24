define([
    'underscore', 'backbone', 'models/user'
], function(_, Backbone, User) {
    var Message = Backbone.View.extend({
        model: User,
        template: '#msg-tpl',
        render: function() {
            var template = _.template($(this.template).html());
            this.$el.html(template(this.model.toJSON()));
            return this;
        }
    });
    return Message;
});