Chat.Views.ChatRoomList = Backbone.View.extend({
    collection: Chat.Collections.Rooms,
    el: '#chat-room-list',
    initialize: function() {
        this.collection.on('add', this.addRoom, this);
    },
    render: function() {
        var self = this;
        self.collection.each(function(model){
            self.addRoom(model);
        });
        return self;
    },
    addRoom: function(room) {
        var view = new Chat.Views.RoomItemView({model: room});
        this.$el.find('.list-group-container').append(view.render().el);
    }
});