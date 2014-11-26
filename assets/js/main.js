define([
    'jquery', 'chat', 'collections/rooms', 'views/chat', 'views/users',
    'views/rooms', 'bootstrap'
], function($, Chat, Rooms, ChatView, UserListView, RoomListView) {
    $('body').tooltip({selector: '[data-toggle="tooltip"]'});
    /*
     * @TODO add rooms list from real store
     */
    var rooms = new Rooms([
        {id: 1, name: 'Room #1'}, {id: 2, name: 'Room #2'}, {id: 3, name: 'Room #3'}
    ]);
    var roomListView = new RoomListView({collection: rooms});
    roomListView.render();
    //create chat after rooms loading
    var chat = new Chat.Room();
    var chatView = new ChatView();
    var userListView = new UserListView({collection: chat.users});
});