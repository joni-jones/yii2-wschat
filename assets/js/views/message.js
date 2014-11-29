Chat.Views.Message = Backbone.View.extend({
    model: Chat.Models.User,
    template: '#msg-tpl',
    render: function() {
        var time = new Date();
        var msg = Chat.makeLink(Chat.encode(this.model.get('message')));
        this.model.set('timestamp', Chat.formatTime(time));
        this.model.set('message', msg);
        if (!this.model.get('type')) {
            this.model.set('type', 'warning');
        }
        var template = _.template($(this.template).html());
        this.$el.html(template(this.model.toJSON()));
        return this;
    }
});