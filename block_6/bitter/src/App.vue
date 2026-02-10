<template>
  <div class="app">
    <header>
      <div class="header-content">
        <h1>Bitter</h1>
        <button v-if="!isHomePage" @click="goToHome" class="home-btn">
          На главную
        </button>
      </div>
      <PostForm @post-created="addNewPost" />
    </header>
    <main>
      <router-view :key="$route.fullPath" :new-post="newPost" />
    </main>
  </div>
</template>

<script>
import { ref, computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import PostForm from "./components/PostForm.vue";

export default {
  components: { PostForm },
  setup() {
    const router = useRouter();
    const route = useRoute();
    const newPost = ref(null);

    const isHomePage = computed(() => route.path === "/");

    const addNewPost = (post) => {
      newPost.value = post;
    };

    const goToHome = () => {
      router.push("/");
    };

    return {
      newPost,
      addNewPost,
      isHomePage,
      goToHome,
    };
  },
};
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Arial, sans-serif;
  padding: 20px;
  background: #f5f8fa;
}

.app {
  max-width: 600px;
  margin: 0 auto;
}

header {
  margin-bottom: 30px;
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

h1 {
  color: #1da1f2;
  margin: 0;
}

.home-btn {
  background: #1da1f2;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 20px;
  cursor: pointer;
  font-size: 14px;
  text-decoration: none;
}

.home-btn:hover {
  background: #0d8bf0;
}

main {
  margin-top: 20px;
}
</style>
