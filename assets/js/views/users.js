Chat.Views.UserListView = Backbone.View.extend({
    collection: Chat.Collections.Users,
    el: '#user-list .list-group-container',
    initialize: function() {
        this.collection.on('add', this.renderUser, this);
    },
    renderUser: function(user) {
        var userView = new Chat.Views.UserView({model: user});
        this.$el.append(userView.render().el);
        return this;
    }
});