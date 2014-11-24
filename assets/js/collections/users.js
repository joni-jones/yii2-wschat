define([
    'backbone', 'models/user'
], function(Backbone, User) {
    var Users = Backbone.Collection.extend({
        model: User
    });
    return Users;
});