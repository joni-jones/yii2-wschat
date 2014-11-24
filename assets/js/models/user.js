define([
    'backbone', 'chat'
], function(Backbone) {
    var User = Backbone.Model.extend({
        defaults: {
            name: '', avatar_16: 'assets/img/user_16.png', avatar_32: 'assets/img/user_32.png', message: ''
        }
    });
    return User;
});