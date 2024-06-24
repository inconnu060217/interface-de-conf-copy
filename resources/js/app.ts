import './bootstrap';

import {createApp} from "vue";

// @ts-ignore
import App from "./pages/App.vue"
import "../css/app.css"
import "../js/components/generics/Icon/css/icons.css"

createApp(App).mount("#app")
