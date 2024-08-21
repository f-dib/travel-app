import { createApp } from 'vue'
import { router } from "./router";
import './style.scss'
import App from './App.vue'

import "bootstrap/dist/css/bootstrap.css";

import '@fortawesome/fontawesome-free/css/all.css';

import '@fortawesome/fontawesome-free/js/all.js';

createApp(App).use(router).mount('#app')

import "bootstrap/dist/js/bootstrap.js";