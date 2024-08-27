import { createRouter, createWebHistory } from "vue-router";

import HomePage from "./pages/HomePage.vue";
import TripForm from "./pages/TripForm.vue";
import SingleTrip from "./pages/SingleTrip.vue";
import SingleDay from "./pages/SingleDay.vue";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomePage,
    },
    {
      path: "/trip-form",
      name: "trip",
      component: TripForm,
    },
    {
      path: '/trips/:id',
      name:'singletrip',
      component: SingleTrip
    },
    {
      path: '/days/:id',
      name:'singleday',
      component: SingleDay
    },
  ],
});

export { router };