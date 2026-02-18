<template>
  <div class="layout">
    <header class="header">
      <div class="container">
        <div class="header-content">
          <h1 @click="$router.push('/')" class="logo">Bitter</h1>
          <button
            v-if="$route.path !== '/'"
            @click="$router.push('/')"
            class="home-btn"
          >
            Главная
          </button>
        </div>
        <PostForm v-if="$route.path === '/'" @post-submited="handleNewPost" />
      </div>
    </header>
    <main class="main-content container">
      <router-view :key="$route.fullPath" :new-post="newPost" />
    </main>
  </div>
</template>

<script>
import { ref } from "vue";
import { useRoute } from "vue-router";
import PostForm from "../components/PostForm.vue";

export default {
  name: "MainLayout",
  components: {
    PostForm,
  },
  setup() {
    const route = useRoute();
    const newPost = ref(null);

    const handleNewPost = (post) => {
      newPost.value = post;
      setTimeout(() => {
        newPost.value = null;
      }, 100);
    };

    return {
      newPost,
      handleNewPost,
      route,
    };
  },
};
</script>

<style scoped>
.layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: #f5f8fa;
}

.header {
  background: white;
  border-bottom: 1px solid #e1e8ed;
  padding: 20px 0;
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.logo {
  color: #1da1f2;
  font-size: 28px;
  margin: 0;
  cursor: pointer;
}

.logo:hover {
  opacity: 0.8;
}

.home-btn {
  background: #1da1f2;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 20px;
  cursor: pointer;
  font-size: 14px;
}

.home-btn:hover {
  background: #0d8bf0;
}

.main-content {
  flex: 1;
  padding: 30px 0;
}

.footer {
  background: white;
  border-top: 1px solid #e1e8ed;
  padding: 20px 0;
  text-align: center;
  color: #657786;
  font-size: 14px;
  margin-top: auto;
}

.container {
  max-width: 600px;
  margin: 0 auto;
  padding: 0 15px;
  width: 100%;
}
</style>
