/**
 * @var currentUserId set in ChatWidget default value 0
 * @var port set in ChatWidget default value 8080
 * @var chatList array of objects set in ChatWidget
 */
$(document).ready(function() {
    $('body').tooltip({selector: '[data-toggle="tooltip"]'});
    var rooms = new Chat.Collections.Rooms(chatList);
    var roomListView = new Chat.Views.ChatRoomList({collection: rooms});
    roomListView.render();

    //create chat after rooms loading
    currentUserId = currentUserId || Cookies.get('chatUserId');
    var chat = new Chat.Room({port: port, currentUserId: currentUserId});
    if (!currentUserId) {
        var addUserView = new Chat.Views.AddUserView();
        addUserView.show();
        Chat.vent.on('user:set_username', function(username) {
            chat.options.username = username;
            chat.init();
        });
    } else {
        chat.init();
    }
    var chatView = new Chat.Views.ChatView();
    var userListView = new Chat.Views.UserListView({collection: chat.users});
});
