<template>
  <DefaultHeader />
  <div class="container">
    <slot />
  </div>
  <DefaultFooter />
</template>

<script setup>
import DefaultHeader from "../components/DefaultHeader.vue";
import DefaultFooter from "../components/DefaultFooter.vue";
</script>
