<template>
  <footer class="app-footer">
    <div class="footer-container">
      <p>&copy; 2024 Bitter — Микроблоги</p>
      <div class="footer-links">
        <span @click="goToHome">Главная</span>
        <span>О проекте</span>
        <span>Условия</span>
      </div>
    </div>
  </footer>
</template>

<script>
import { useRouter } from "vue-router";

export default {
  name: "AppFooter",
  setup() {
    const router = useRouter();

    const goToHome = () => {
      router.push("/");
    };

    return {
      goToHome,
    };
  },
};
</script>

<style scoped>
.app-footer {
  background: white;
  border-top: 1px solid #e1e8ed;
  padding: 20px 0;
  margin-top: 40px;
}

.footer-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 0 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #657786;
  font-size: 13px;
}

.footer-links {
  display: flex;
  gap: 15px;
}

.footer-links span {
  cursor: pointer;
}

.footer-links span:hover {
  color: #1da1f2;
  text-decoration: underline;
}
</style>
