<template>
    <div class="right">
        <div class="top">
            <span>System Messages</span>
            <div style="float: right; margin-right: 10px"  class="dropdown">
                <i class="fa fa-ellipsis-v" style=" color: #6b6b6b" aria-hidden="true"></i>
                <div class="dropdown-content">

                    <a   href="#" @click.prevent="clear"> Clear Chat</a>
                </div>
            </div>

        </div>

        <div ref="chat" class="chat" style="max-height: 460px; overflow-y: scroll;">


            <div   class="card-text">
                <p  :class="{'bubble you':chat.type === 0,'bubble me':chat.type === 1}" v-for="chat in chats" :key="chat.id">
                    {{chat.message}}
                    <br>
                    <span style="font-size:10px">send {{chat.send_at}}</span>

                </p>
            </div>

   <!--             <div class="card-text"   v-for="chat in chats" :key="chat.id">

                    <div class="bubble you" v-if="chat.type === 1">
                        {{chat.message}}
                        <i v-if="chat.read_at!=null" class="fa fa-check" style=" color: #fff9fe" aria-hidden="true">
                        </i>

                        <span v-if="chat.sent_at" style="font-size:9px">{{chat.read_at}}</span>
                    </div>
                    <div class="bubble me" v-else>
                        {{chat.message}}
                        <i v-if="chat.read_at!=null" class="fa fa-check" style=" color: #00b0ff" aria-hidden="true">
                        </i>
                        <span v-if="chat.read_at" style="font-size:9px">{{chat.read_at}}</span>

                    </div>
                </div>-->
        </div>
        <div class="write" v-if="current.is_admin">
            <form class="card-footer"  @submit.prevent="send">
            <input type="text" placeholder="Broadcast message"
                   v-model="message" />
            <a  type="submit" style="cursor: pointer" @click.prevent="send" class="write-link send"></a>
            </form>
        </div>
    </div>
</template>



<script>
    export default {
        props: ['current'],
        data() {
            return {
                chats: [],
                message: null,
                isTyping: false,
                echo: window.Echo
            }
        },
        watch: {
            message(value) {
                if (value) {
                    this.echo.private(`Chat.1`).whisper("client-typing", {
                        name: auth.username
                    });
                }
            }
        },

        created() {
            this.read();

            this.getAllMessages();

            this.echo.private(`Chat.1`).listen(
                "PrivateChatEvent",
                (e) => {
                    this.chats.push({ message: e.content, type: 1, send_at: "Just Now" })
                    this.scrollToBottom();
                }
            );


            this.echo.private(`Chat.1`).listen("MsgReadEvent", (e) =>
                {this.chats.forEach(
                    chat => (chat.id == e.chat.id ? (chat.read_at = e.chat.read_at) : "")
                )}
            );


            this.echo.private(`Chat.1`).listenForWhisper(
                "client-typing",
                (e) => {
                    this.isTyping = true;
                    setTimeout(() => {
                        this.isTyping = false;
                    }, 2000);
                }
            );
        },

        updated() {
            this.scrollToBottom();
        },

        methods: {
            getAllMessages() {
                let locale = this.$trans.getLocale();
                axios({
                    method: 'post',
                    url: `${locale}/session/1/chats`
                }).then(res => (this.chats = res.data.data));
            },
            scrollToBottom() {
                const scrollContainer = this.$refs.chat;
                scrollContainer.scrollTop = scrollContainer.scrollHeight - scrollContainer.clientHeight;
            },
            send() {
                if (this.message) {
                    this.pushToChats(this.message);
                    let locale = this.$trans.getLocale();
                    axios({
                        method: 'post',
                        url: `${locale}/send/1`,
                        data: {
                                message: this.message,
                                to_user: 10000
                            }
                    }).then(res => (this.chats[this.chats.length - 1].id = res.data));
                    this.message = null;
                }
            },
            pushToChats(message) {
                this.chats.push({
                    message: message,
                    type: 0,
                    read_at: null,
                    send_at: "Just now"
                });

            },
            close() {
                this.$emit('close');
            },
            clear() {
                let locale = this.$trans.getLocale();
                axios({
                    method: 'post',
                    url: `${locale}/session/1/clear`
                }).then(res => {
                    this.chats = [];
                })
            },

            read() {
                let locale = this.$trans.getLocale();
                axios({
                    method: 'post',
                    url: `${locale}/session/1/read`
                });
            }

        },

    };
</script>

<style>

    .text-danger{
     color: red !important;
    }
    .card-body {
        overflow-y: scroll;
    }
    .dropbtn {
        background-color: #4CAF50;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    /* The container <div> - needed to position the dropdown content */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Dropdown Content (Hidden by Default) */
    .dropdown-content {
        margin-top: -20px;
        margin-left: -150px;
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    /* Change color of dropdown links on hover */
    .dropdown-content a:hover {background-color: #f1f1f1}

    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* Change the background color of the dropdown button when the dropdown content is shown */
    .dropdown:hover .dropbtn {
        background-color: #3e8e41;
    }
</style>
