{include file='header.tpl'}
<style>
    .txt-break{
        word-break: break-all;
        white-space: initial;
    }
</style>
            <div class="row mt-5 border border-secondarys">
                <div class="col-12" id="messages" style="height: 75vh; overflow-y: scroll">

                </div>
                <div class="col-12">
                    <form action="">
                        <div class="row">
                            <div class="col-9 col-sm-9 col-md-10 p-0">
                                <input id="m" class="form-control" autocomplete="off" />
                            </div>

                            <div class="col-3 col-sm-3 col-md-2 p-0">
                                <button class="btn btn-info w-100">Send</button>
                            </div>

                        </div>


                    </form>
                </div>
            </div>
<audio id="audio" src="/templates/default/bruh.mp3"></audio>
<script src="/templates/default/assets/js/socket.io.js"></script>
<script src="/templates/default/assets/js/jquery.min.js"></script>
<script src="/templates/default/assets/js/moment.js"></script>

<script>
    {literal}
    var socket = io('http://159.69.219.35:6599');
    var from = 0;
    var scroll = false;
    $(window).on("load", function (){
        setPageTitle("Chat");
        $(function () {
            $('form').submit(function(e) {
                e.preventDefault(); // prevents page reloading
                let msg = {
                    msg: $('#m').val(),
                    user: '{/literal}{$user}{literal}'
                }
                socket.emit('chat message', msg);
                $('#m').val('');
                return false;
            });
            socket.on('chat message', function(msg){
                var element = document.getElementById("messages");
                var messages = $('#messages');
                var node = $('#messages')[0];
                var data = JSON.parse(JSON.stringify(msg));
                if(node.scrollTop + node.offsetHeight - node.scrollHeight >= 0) {
                    messages.append($('<div class="row border-top">' +
                        '<div class="col-2 col-sm-2 col-md-1 col-lg-1 p-0 d-flex justify-content-start m-auto">' +
                        data['user'] +
                        '</div>' +
                        '<div class="col-5 col-sm-6 col-md-8 col-lg-9 p-0 txt-break m-auto">' +
                        data['msg'] +
                        '</div>' +
                        '<div class="col-5 col-sm-4 col-md-3 col-lg-2 p-0 d-flex justify-content-end m-auto" >' +
                        '<small>' + moment().format("HH:mm:ss DD.MM.YYYY") + '</small>' +
                        '</div>' +
                        '</div>'));
                    element.scrollTop = element.scrollHeight;
                } else {
                    messages.append($('<div class="row border-top">' +
                        '<div class="col-2 col-sm-2 col-md-1 col-lg-1 p-0 d-flex justify-content-start m-auto">' +
                        data['user'] +
                        '</div>' +
                        '<div class="col-5 col-sm-6 col-md-8 col-lg-9 p-0 txt-break m-auto">' +
                        data['msg'] +
                        '</div>' +
                        '<div class="col-5 col-sm-4 col-md-3 col-lg-2 p-0 d-flex justify-content-end m-auto" >' +
                        '<small>' + moment().format("HH:mm:ss DD.MM.YYYY") + '</small>' +
                        '</div>' +
                        '</div>'));
                }
                var audio = document.getElementById("audio");
                audio.volume = 1;
                audio.play();
                from += 1;
            });
            socket.on('getMessagesInit', function(msg){
                if (!scroll) {
                    msg = msg.reverse();
                    msg.forEach(el => $('#messages').append($('<div class="row border-top">' +
                        '<div class="col-2 col-sm-2 col-md-1 col-lg-1 p-0 d-flex justify-content-start m-auto">' +
                        el['username'] +
                        '</div>' +
                        '<div class="col-5 col-sm-6 col-md-8 col-lg-9 p-0 txt-break m-auto">' +
                        el['msg'] +
                        '</div>' +
                        '<div class="col-5 col-sm-4 col-md-3 col-lg-2 p-0 d-flex justify-content-end m-auto" >' +
                        '<small>' + moment(el['date_created'], "YYYY-MM-DD HHS:mm:ss").add(3, 'hours').format("HH:mm:ss DD.MM.YYYY") + '</small>' +
                        '</div>' +
                        '</div>')));
                    var element = document.getElementById("messages");
                    element.scrollTop = element.scrollHeight;
                    updateFrom();
                    scroll = true;
                }
            });
            socket.on('getMessages', function(msg){
                msg.forEach(el => $('#messages').prepend($('<div class="row border-top">' +
                    '<div class="col-2 col-sm-2 col-md-1 col-lg-1 p-0 d-flex justify-content-start m-auto">' +
                    el['username'] +
                    '</div>' +
                    '<div class="col-5 col-sm-6 col-md-8 col-lg-9 p-0 txt-break m-auto">' +
                    el['msg'] +
                    '</div>' +
                    '<div class="col-5 col-sm-4 col-md-3 col-lg-2 p-0 d-flex justify-content-end m-auto" >' +
                    '<small>' + moment(el['date_created'], "YYYY-MM-DD HH:mm:ss").add(3, 'hours').format("HH:mm:ss DD.MM.YYYY")+ '</small>' +
                    '</div>' +
                    '</div>')));

                if (!scroll){
                    var element = document.getElementById("messages");
                    element.scrollTop = element.scrollHeight;
                    scroll = true;
                }
                updateFrom();
            });
        });
    });

    function updateFrom(){
        from += 30;
    }
    window.onload = function(){
        setInterval(function () {
            if(document.getElementById("messages").scrollTop === 0){
                socket.emit('getMessages', from);
            }
        }, 1000);
    }
    {/literal}
</script>
{include file='footer.tpl'}
