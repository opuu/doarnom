<template>
  <section class="about-page">
    <h1>About PetAdopt</h1>
    <p>
      PetAdopt connects loving families with pets in need of a forever home.
      We're passionate about animal welfare and committed to making adoptions
      seamless.
    </p>

    <h2>Our Mission</h2>
    <p>
      To rescue, rehabilitate, and rehome pets by creating a supportive
      community and providing resources for adopters and shelters.
    </p>

    <h2>Meet the Team</h2>
    <div class="team-grid">
      <div class="team-member">
        <h3>Obaydur Rahman</h3>
        <p>213902018</p>
      </div>
      <div class="team-member">
        <h3>Abid Hossain</h3>
        <p>213902021</p>
      </div>
      <div class="team-member">
        <h3>Tanjim Mahtab</h3>
        <p>213902007</p>
      </div>
    </div>
  </section>

  <HomeCTA />
</template>

<script setup>
import HomeCTA from "@/components/HomeCTA.vue";
</script>

<style scoped lang="scss">
.about-page {
  padding: 2rem 0;
  color: #333;

  h1 {
    font-size: 2.5rem;
    color: var(--p-primary-color);
    margin-bottom: 1.5rem;
    font-weight: 700;
  }

  p {
    font-size: 1.125rem;
    color: #555;
    margin-bottom: 1rem;
    line-height: 1.6;
  }

  h2 {
    font-size: 2rem;
    color: var(--p-primary-color);
    margin: 2rem 0 1rem;
    font-weight: 600;
  }

  .team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;

    .team-member {
      background: #f7f7f7;
      padding: 1.5rem;
      border-radius: 1rem;
      text-align: center;

      h3 {
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
        color: var(--p-primary-color);
      }

      p {
        margin: 0;
        font-size: 1rem;
        color: #555;
      }
    }
  }
}
</style>
