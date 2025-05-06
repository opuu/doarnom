<template>
  <HomeHero />
  <HomePets categoryName="Cats" />
  <HomePets categoryName="Dogs" />
  <HomeCTA />
</template>

<script setup>
import HomeHero from "@/components/HomeHero.vue";
import HomePets from "@/components/HomePets.vue";
import HomeCTA from "@/components/HomeCTA.vue";
</script>

<style scoped></style>
