<template>
  <div class="idp-header">
    <div class="container">
      <div class="idp-header__logo" @click="$router.push('/')">
        <img src="@/assets/logo.png" alt="Logo" />
      </div>
      <div class="idp-header__menu">
        <ul class="idp-header__menu-list">
          <li class="idp-header__menu-item">
            <router-link to="/">Home</router-link>
          </li>
          <li class="idp-header__menu-item">
            <router-link to="/search">Adopt</router-link>
          </li>
          <li class="idp-header__menu-item">
            <router-link to="/about">About</router-link>
          </li>
          <li class="idp-header__menu-item">
            <router-link to="/contact">Contact</router-link>
          </li>
        </ul>
      </div>
      <div class="idp-header__actions" v-if="!auth.isAuthenticated">
        <Button
          label="Login"
          icon="pi pi-sign-in"
          class="p-button-text"
          @click="$router.push('/login')"
        />
        <Button
          label="Sign Up"
          icon="pi pi-user-plus"
          class="p-button-outlined"
          @click="$router.push('/signup')"
        />
      </div>
      <div class="idp-header__actions" v-else>
        <Button
          label="Manage"
          icon="pi pi-cog"
          class="p-button-text"
          @click="$router.push('/manage')"
        />
        <Button
          label="Requests"
          icon="pi pi-envelope"
          class="p-button-text"
          @click="$router.push('/adoptions')"
        />
        <Button
          label="Logout"
          icon="pi pi-sign-out"
          class="p-button-outlined"
          @click="auth.logout()"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import Button from "primevue/button";
import { useAuthStore } from "@/stores/auth";
const auth = useAuthStore();

window.addEventListener("scroll", () => {
  const header = document.querySelector(".idp-header");
  if (window.scrollY > 0) {
    header.classList.add("shadow");
  } else {
    header.classList.remove("shadow");
  }
});
</script>

<style scoped lang="scss">
.idp-header {
  background-color: #fff;
  position: sticky;
  top: 0;
  z-index: 1000;

  &.shadow {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
  }

  .idp-header__logo img {
    height: 50px;
  }

  .idp-header__menu {
    .idp-header__menu-list {
      display: flex;
      list-style: none;
      padding: 0;
      margin: 0;

      .idp-header__menu-item {
        margin-right: 20px;

        a {
          text-decoration: none;
          color: #333;
          font-weight: 500;

          &.router-link-active {
            color: #007bff;
          }
        }
      }
    }
  }

  .idp-header__actions {
    display: flex;
    gap: 10px;
  }
}
</style>
