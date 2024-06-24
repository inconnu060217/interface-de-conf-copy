import './bootstrap';

import {createApp} from "vue";
import { createStore } from "vuex";


// @ts-ignore
import App from "./pages/App.vue"
// @ts-ignore
import Accueil from "./pages/Accueil.vue";
// @ts-ignore
import AdvancedParameter from "./pages/AdvancedParameter.vue";
// @ts-ignore
import Call from "./pages/Call.vue";
// @ts-ignore
import Chat from "./pages/Chat.vue";
// @ts-ignore
import Email from "./pages/Email.vue";
// @ts-ignore
import Messaging from "./pages/Messaging.vue";
// @ts-ignore
import Unauthorized from "./pages/Unauthorized.vue";
import {loaderStore} from "./stores/useLoaderStore";


import "../css/app.css"
import "../js/components/generics/Icon/css/icons.css"
const app = createApp({});

const store = createStore({
    modules: {
        loader: loaderStore
    },
});


// page
app.component("app-page", App);
app.component("call-page", Call);
app.component("email-page", Email);
app.component("chat-page", Chat);
app.component("messaging-page", Messaging);
app.component("acceuil-page", Accueil);
app.component("advanced-page", AdvancedParameter);
app.component("unauthorized", Unauthorized);

app.use(store);

app.mount("#app");
