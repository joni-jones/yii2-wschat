Chat.Models.User = Backbone.Model.extend({
    defaults: {
        username: '', avatar_16: imgPath + '/avatar_16.png', avatar_32: imgPath + '/avatar_32.png',
        message: ''
    }
});