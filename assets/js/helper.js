(function() {
    window.Helper = {
        Message: {}
    };
    Helper.dict = {
        'ru': {
            'New message from': 'Сообщение от',
            'Connect to chat': 'Подключился к чату',
            'Left this chat': 'Вышел из чата',
            'Send message': 'Отправить сообщение',
            'Current room is not available': 'Текущий чат недоступен',
            'Copied to clipboard': 'Скопировано в буфер обмена'
        }
    };
    Helper.t = function(message) {
        var lang = $.cookie('chatLang') || 'en';
        if (lang == 'en') {
            return message;
        }
        return Helper.dict[lang][message];
    };
    Helper.Message.info = function(message) {
        var opts = {
            text: message,
            type: 'info',
            history: false,
            icon: false,
            styling: 'bootstrap3'
        };
        new PNotify(opts);
    };
    Helper.Message.error = function(message) {
        var opts = {
            text: message,
            type: 'error',
            history: false,
            icon: false,
            styling: 'bootstrap3'
        };
        new PNotify(opts);
    };
    return Helper;
}());