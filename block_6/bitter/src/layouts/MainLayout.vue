<template>
  <div class="layout">
    <AppHeader @post-created="handleNewPost" />

    <main class="main-content">
      <router-view :key="$route.fullPath" :new-post="newPost" />
    </main>

    <AppFooter />
  </div>
</template>

<script>
import { ref } from "vue";
import { useRouter } from "vue-router";
import AppHeader from "../components/AppHeader.vue";
import AppFooter from "../components/AppFooter.vue";

export default {
  name: "MainLayout",
  components: {
    AppHeader,
    AppFooter,
  },
  setup() {
    const router = useRouter();
    const newPost = ref(null);

    const handleNewPost = (post) => {
      newPost.value = post;

      // Если мы не на главной, после создания поста можно показать уведомление
      if (router.currentRoute.value.path !== "/") {
        // Пост все равно появится в соответствующих разделах благодаря фильтрации
        console.log("Пост создан:", post);
      }

      // Сбрасываем newPost через небольшую задержку
      setTimeout(() => {
        newPost.value = null;
      }, 100);
    };

    return {
      newPost,
      handleNewPost,
    };
  },
};
</script>

<style scoped>
.layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.main-content {
  max-width: 600px;
  margin: 0 auto;
  padding: 0 15px;
  flex: 1;
  width: 100%;
}
</style>
