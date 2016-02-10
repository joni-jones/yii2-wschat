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
            'Copied to clipboard': 'Скопировано в буфер обмена',
            'Connection error. Try to reload page': 'Ошибка соединения. Попробуйте обновить страницу',
            'Something wrong. Connection will be closed': 'Произошла ошибка. Соединение будет закрыто'
        }
    };
    Helper.t = function(message) {
        var lang = Cookies.get('chatLang') || 'en';
        if (lang === 'en') {
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
    Helper.uid = function() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
            return v.toString(16);
        });
    };
    return Helper;
}());