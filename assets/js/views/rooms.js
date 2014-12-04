Chat.Views.ChatRoomList = Backbone.View.extend({
    collection: Chat.Collections.Rooms,
    el: '#chat-room-list',
    events: {
        'click #add-chat': 'showChatModal',
        'click #exit-chat': 'logout'
    },
    initialize: function() {
        this.collection.on('add', this.addRoom, this);
        Chat.vent.on('chat:add', function(chat) {
            this.collection.add(chat);
        }, this);
        this.chatModalView = new Chat.Views.AddRoomView();
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
    },
    showChatModal: function() {
        this.chatModalView.show();
    },
    logout: function() {
        location.href = (location.origin);
    }
});