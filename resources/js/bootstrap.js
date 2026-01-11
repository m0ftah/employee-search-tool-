import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Laravel Echo for real-time broadcasting
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

// Initialize Echo with fallback for development
const pusherKey =
    import.meta.env.VITE_PUSHER_APP_KEY || "local-development-key";
const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER || "mt1";
const pusherHost = import.meta.env.VITE_PUSHER_HOST || window.location.hostname;
const pusherPort =
    import.meta.env.VITE_PUSHER_PORT ||
    (window.location.protocol === "https:" ? 443 : 80);
const pusherScheme =
    import.meta.env.VITE_PUSHER_SCHEME ||
    window.location.protocol.replace(":", "");

window.Echo = new Echo({
    broadcaster: "pusher",
    key: pusherKey,
    cluster: pusherCluster,
    wsHost: pusherHost,
    wsPort: pusherPort,
    wssPort: pusherPort,
    forceTLS: pusherScheme === "https",
    enabledTransports: ["ws", "wss"],
    disableStats: true,
});
