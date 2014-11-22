$(document).ready(function() {
    var chat = new Chat.Room({url: 'ws.chat'});
    var chatView = new Chat.Views.Chat();
    var userList = new Chat.Views.UserList({collection: chat.users});
});