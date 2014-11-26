define([
    'backbone'
], function(Backbone) {
    var ChatRoom = Backbone.Model.extend({
        defaults: {id: '', name: ''}
    });
    return ChatRoom;
});