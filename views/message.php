<script id="msg-tpl" type="text/template">
    <dl class="dl-horizontal">
        <dt>
        <div class="chat-item">
            <span><%=username%></span><img src="<%=avatar_16%>" width="16" height="16" class="img-circle">
        </div>
        </dt>
        <dd>
            <div class="chat-msg text-left alert-<%=type%>">
                <span class="msg-time"><%=timestamp%></span>
                <div><%=message%></div>
            </div>
        </dd>
    </dl>
</script>
