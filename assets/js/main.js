$(document).ready(function() {
    $('body').tooltip({selector: '[data-toggle="tooltip"]'});
    /*
     * @TODO add rooms list from real store
     */
    var rooms = new Chat.Collections.Rooms([
        {id: 1, name: 'Room #1'}, {id: 2, name: 'Room #2'}, {id: 3, name: 'Room #3'}
    ]);
    var roomListView = new Chat.Views.ChatRoomList({collection: rooms});
    roomListView.render();
    //create chat after rooms loading
    var chat = new Chat.Room();
    var chatView = new Chat.Views.ChatView();
    var userListView = new Chat.Views.UserListView({collection: chat.users});
});