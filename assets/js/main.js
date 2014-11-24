define([
    'chat', 'views/chat', 'views/users'
], function(Chat, ChatView, UserListView) {
    var chat = new Chat.Room();
    var chatView = new ChatView();
    var userListView = new UserListView({collection: chat.users});
});