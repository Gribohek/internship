<template>
  <nav class="navigation">
    <button v-if="!isHomePage" @click="goToHome" class="nav-btn home-btn">
      ← Главная
    </button>
    <div v-else class="nav-placeholder"></div>
  </nav>
</template>

<script>
import { useRouter, useRoute } from "vue-router";
import { computed } from "vue";

export default {
  name: "Navigation",
  setup() {
    const router = useRouter();
    const route = useRoute();

    const isHomePage = computed(() => route.path === "/");

    const goToHome = () => {
      router.push("/");
    };

    return {
      isHomePage,
      goToHome,
    };
  },
};
</script>

<style scoped>
.navigation {
  display: flex;
  align-items: center;
}

.nav-btn {
  background: #1da1f2;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 20px;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
  transition: background 0.2s;
}

.nav-btn:hover {
  background: #0d8bf0;
}

.nav-placeholder {
  width: 100px;
}
</style>
