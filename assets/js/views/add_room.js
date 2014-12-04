Chat.Views.AddRoomView = Backbone.View.extend({
    el: '#add-room-modal',
    events: {
        'click #add-room-btn': 'add'
    },
    show: function() {
        this.$el.modal('show');
    },
    hide: function() {
        this.$el.modal('hide');
    },
    add: function() {
        var title = this.$el.find('[name="title"]').val();
        if (title) {
            this.hide();
            Chat.vent.trigger('chat:add', new Chat.Models.ChatRoom({id: Helper.uid().substring(24), title: title}));
        }
    }
});