import { defineStore } from "pinia";
import api from "@/services/api.js";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: JSON.parse(localStorage.getItem("user") || "{}"),
    token: localStorage.getItem("token"),
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
    getUser: (state) => state.user,
  },
  actions: {
    async login(credentials) {
      const { data } = await api.post("/auth/signin", credentials);
      this.token = data.payload.token;
      localStorage.setItem("token", data.payload.token);
      this.user = data.payload.user;
      localStorage.setItem("user", JSON.stringify(data.payload.user));
    },
    async signup(payload) {
      const { data } = await api.post("/auth/signup", payload);
    },
    logout() {
      this.token = null;
      this.user = null;
      localStorage.removeItem("token");
      localStorage.removeItem("user");
    },
  },
});
