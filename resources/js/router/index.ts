import {createRouter, createWebHistory} from "vue-router";

// @ts-ignore
import App from "../pages/App.vue";
// @ts-ignore
import Accueil from "../pages/Accueil.vue";
// @ts-ignore
import AdvancedParameter from "../pages/AdvancedParameter.vue";
// @ts-ignore
import Call from "../pages/Call.vue";
// @ts-ignore
import Chat from "../pages/Chat.vue";
// @ts-ignore
import Email from "../pages/Email.vue";
// @ts-ignore
import Messaging from "../pages/Messaging.vue";

const routes = [
    {
        path: "/",
        component: App,
        name: 'Home',
    },
    {
        path: "/accueil",
        component: Accueil,
        name: 'Accueil',
    },
    {
        path: "/advanced",
        component: AdvancedParameter,
        name: 'Advanced',
    },
    {
        path: "/call",
        component: Call,
        name: 'Call',
    },
    {
        path: "/chat",
        component: Chat,
        name: 'Chat',
    },
    {
        path: "/email",
        component: Email,
        name: 'Email',
    },
    {
        path: "/messaging",
        component: Messaging,
        name: 'Messaging',
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
