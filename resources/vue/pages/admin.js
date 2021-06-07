import { createApp } from 'vue';
import App from '../components/AdminChatComponent';
import Language from '../../js/Language';

let app = createApp(App);

app.config.globalProperties.$trans = Language.getInstance();

app.mount('admin-component');
