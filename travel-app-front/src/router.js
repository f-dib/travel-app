import { createRouter, createWebHistory } from "vue-router";

import HomePage from "./pages/HomePage.vue";
import TripForm from "./pages/TripForm.vue";
import SingleTrip from "./pages/SingleTrip.vue";
import SingleDay from "./pages/SingleDay.vue";
import SingleStage from "./pages/SingleStage.vue";
import StageForm from "./pages/StageForm.vue";
import EditTripForm from "./pages/EditTripForm.vue";

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
    {
      path: '/stages/:id',
      name:'singlestage',
      component: SingleStage
    },
    {
      path: '/stage-form/:id',
      name:'stage',
      component: StageForm
    },
    {
      path: '/edit-trip/:id',
      name:'edittrip',
      component: EditTripForm
    },
  ],
});

export { router };