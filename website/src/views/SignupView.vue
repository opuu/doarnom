<template>
  <section class="auth-page">
    <h1>Signup</h1>
    <p>Create new account.</p>
    <form class="auth-form" @submit.prevent="handleSubmit">
      <div class="field">
        <label for="name">Name</label>
        <InputText id="name" v-model="form.name" required />
      </div>
      <div class="field">
        <label for="email">Email</label>
        <InputText id="email" v-model="form.email" type="email" required />
      </div>
      <div class="field">
        <label for="phone">Phone</label>
        <InputText id="phone" v-model="form.phone" required />
      </div>
      <div class="field">
        <label for="password">Password</label>
        <Password
          id="password"
          v-model="form.password"
          toggleMask
          feedback="false"
          required
        />
      </div>
      <Button
        type="submit"
        label="Sign Up"
        class="p-button-rounded p-button-lg"
      />
      <p class="redirect">
        Already have an account?
        <router-link to="/login">Login</router-link>
      </p>
    </form>
  </section>
</template>

<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Button from "primevue/button";

const router = useRouter();
const auth = useAuthStore();
const form = reactive({ name: "", email: "", phone: "", password: "" });

async function handleSubmit() {
  await auth.signup(form);
  router.push("/login");
}
</script>

<style scoped lang="scss">
.auth-page {
  padding: 2rem 0;
  color: #333;

  h1 {
    font-size: 2.5rem;
    color: var(--p-primary-color);
    margin-bottom: 1rem;
    font-weight: 700;
    text-align: center;
  }

  p {
    font-size: 1.125rem;
    color: #555;
    margin-bottom: 2rem;
    line-height: 1.6;
    text-align: center;
  }

  .auth-form {
    display: grid;
    gap: 1.5rem;
    max-width: 400px;
    margin: 0 auto;

    .field {
      display: flex;
      flex-direction: column;

      label {
        margin-bottom: 0.5rem;
        font-weight: 500;
      }
    }

    .redirect {
      text-align: center;
      font-size: 0.9rem;

      a {
        color: var(--p-primary-color);
        font-weight: 600;
        text-decoration: none;

        &:hover {
          text-decoration: underline;
        }
      }
    }
  }
}
</style>
