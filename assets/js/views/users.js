define([
    'backbone', 'collections/users', 'views/user'
], function(Backbone, Users, UserView) {
    var UserListView = Backbone.View.extend({
        collection: Users,
        el: '#user-list .list-group-container',
        initialize: function() {
            this.collection.on('add', this.renderUser, this);
        },
        renderUser: function(user) {
            var userView = new UserView({model: user});
            this.$el.append(userView.render().el);
            return this;
        }
    });
    return UserListView;
});