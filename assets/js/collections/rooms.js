define([
    'backbone', 'models/room'
], function(Backbone, ChatRoom) {
    var Rooms = Backbone.Collection.extend({
        model: ChatRoom
    });
    return Rooms;
});