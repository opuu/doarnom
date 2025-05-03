<template>
  <section class="home-cta">
    <div class="home-cta__content">
      <h2>Ready to Make a Difference?</h2>
      <p>
        Join our community of pet lovers and help find forever homes for
        adorable pets today.
      </p>
      <router-link to="/signup" class="cta-btn">Get Started</router-link>
    </div>
  </section>
</template>

<script>
export default {
  name: "HomeCTA",
};
</script>

<style scoped lang="scss">
.home-cta {
  background: #f7f7f7;
  padding: 4rem 1rem;
  text-align: center;
  border-radius: 1rem;
  margin: 2rem 0;

  &__content {
    max-width: 600px;
    margin: 0 auto;

    h2 {
      font-size: 2.5rem;
      color: var(--p-primary-color);
      margin-bottom: 1rem;
      font-weight: 700;
    }

    p {
      font-size: 1.125rem;
      color: #555;
      margin-bottom: 2rem;
    }

    .cta-btn {
      display: inline-block;
      padding: 0.75rem 2rem;
      background: var(--p-primary-color);
      color: #fff;
      border-radius: 30px;
      font-size: 1.1rem;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.2s;
      box-shadow: 0 4px 16px rgba(255, 111, 97, 0.15);

      &:hover {
        background: var(--p-primary-hover-color);
      }
    }
  }
}
</style>
