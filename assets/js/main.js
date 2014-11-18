$(document).ready(function() {
    var chat = new Chat({url: 'ws.chat'});

    $('#send-msg').click(function() {
        chat.send($('[name="chat_message"]').val());
    });
});