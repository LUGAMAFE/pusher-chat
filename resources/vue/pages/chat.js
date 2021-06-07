import { createApp } from 'vue';
import App from '../components/PrivateChatComponent';
import Language from '../../js/Language';

let app = createApp(App);

app.config.globalProperties.$trans = Language.getInstance();

app.mount('chat-component');
