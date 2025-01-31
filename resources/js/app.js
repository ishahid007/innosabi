import './bootstrap';
import { createApp } from 'vue';
import Suggestions from './components/suggestions/suggestionsIndex.vue';

const app = createApp({});
app.component('Suggestions', Suggestions); // Register the component
app.mount('#app'); // Mount to an element with id="app"
