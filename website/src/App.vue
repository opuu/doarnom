<script setup>
import Layout from "./layouts/default.vue";
</script>

<template>
  <Layout>
    <router-view />
  </Layout>
</template>

<style lang="scss">
html,
body {
  margin: 0;
  padding: 0;
  font-family: "Poppins", sans-serif;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;

  @media (max-width: 768px) {
    max-width: 100%;
    padding: 1rem;
  }
}
</style>
