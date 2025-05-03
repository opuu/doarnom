<template>
  <footer class="idp-footer">
    <div class="container idp-footer__top">
      <div class="idp-footer__links">
        <router-link to="/">Home</router-link>
        <router-link to="/about">About</router-link>
        <router-link to="/contact">Contact</router-link>
      </div>
      <div class="idp-footer__social">
        <a href="#" aria-label="Facebook"><i class="pi pi-facebook"></i></a>
        <a href="#" aria-label="Twitter"><i class="pi pi-twitter"></i></a>
        <a href="#" aria-label="Instagram"><i class="pi pi-instagram"></i></a>
      </div>
    </div>
    <div class="container idp-footer__bottom">
      <p>© 2025 PetAdopt. All rights reserved.</p>
    </div>
  </footer>
</template>

<script setup></script>

<style scoped lang="scss">
.idp-footer {
  background-color: #f7f7f7;
  padding: 2rem 0;
  color: #555;

  &__top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
  }

  &__links {
    display: flex;
    gap: 1.5rem;

    a {
      text-decoration: none;
      color: #333;
      font-weight: 500;

      &:hover {
        color: var(--p-primary-color);
      }
    }
  }

  &__social {
    display: flex;
    gap: 1rem;

    a {
      font-size: 1.25rem;
      color: #777;

      &:hover {
        color: var(--p-primary-color);
      }
    }
  }

  &__bottom {
    text-align: center;
    margin-top: 1rem;

    p {
      margin: 0;
      font-size: 0.9rem;
      color: #777;
    }
  }
}
</style>
