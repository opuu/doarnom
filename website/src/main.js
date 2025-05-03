import { createApp } from "vue";
import { createPinia } from "pinia";
import PrimeVue from "primevue/config";
import Aura from "@primeuix/themes/aura";
import "primeicons/primeicons.css";
import { definePreset } from "@primeuix/themes";
import ToastService from "primevue/toastservice";

import App from "./App.vue";
import router from "./router";

const app = createApp(App);

const PetAdopt = definePreset(Aura, {
  semantic: {
    primary: {
      50: "{blue.50}",
      100: "{blue.100}",
      200: "{blue.200}",
      300: "{blue.300}",
      400: "{blue.400}",
      500: "{blue.500}",
      600: "{blue.600}",
      700: "{blue.700}",
      800: "{blue.800}",
      900: "{blue.900}",
      950: "{blue.950}",
    },
  },
});

app.use(PrimeVue, {
  ripple: true,
  inputStyle: "filled",

  theme: {
    preset: PetAdopt,
    options: {
      darkModeSelector: false,
    },
  },
});
app.use(ToastService);

app.use(createPinia());
app.use(router);

app.mount("#app");
