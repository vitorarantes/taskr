import Vue from "vue";
import Vuex from "vuex";

import auth from "./modules/auth";
import base from "./modules/base";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        auth,
        base
    },
});
