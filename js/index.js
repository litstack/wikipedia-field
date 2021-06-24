import LitWikipedia from './LitWikipedia.vue';

window.Lit.booting(Vue => {
    Vue.component('lit-wikipedia', LitWikipedia);
});
