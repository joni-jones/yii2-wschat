define([
    'jquery', 'chat', 'views/chat', 'views/users', 'bootstrap'
], function($, Chat, ChatView, UserListView) {
    $('body').tooltip({selector: '[data-toggle="tooltip"]'});
    var chat = new Chat.Room();
    var chatView = new ChatView();
    var userListView = new UserListView({collection: chat.users});
});