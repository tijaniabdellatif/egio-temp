require('./bootstrap');

import { createApp } from "vue"
//import SearchComponent from "./components/SearchComponent.vue"
import TestComponent from "./components/TestComponent.vue"


createApp({
    components: {
        //SearchComponent,
        TestComponent
    }
})
.mixin(require('./translation'))
.mount('#v2-app');