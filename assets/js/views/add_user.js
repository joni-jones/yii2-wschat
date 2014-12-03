Chat.Views.AddUserView = Backbone.View.extend({
    el: '#add-user-modal',
    events: {
        'click #add-user-btn': 'add'
    },
    show: function() {
        this.$el.modal({
            backdrop: 'static',
            keyboard: false
        });
    },
    add: function() {
        var username = this.$el.find('[name="username"]').val();
        if (username) {
            this.$el.modal('hide');
            Chat.vent.trigger('user:set_username', username);
        }
    }
});