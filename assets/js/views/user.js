define([
    'underscore', 'backbone', 'models/user'
], function(_, Backbone, User) {
    var UserView = Backbone.View.extend({
        model: User,
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
    return UserView;
});