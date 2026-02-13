<template>
  <header class="app-header">
    <div class="header-container">
      <div class="header-left">
        <h1 class="logo" @click="goToHome">Bitter</h1>
        <Navigation />
      </div>

      <div class="header-right">
        <span class="current-user" @click="goToMyProfile">@current_user</span>
      </div>
    </div>

    <div class="post-form-container">
      <PostForm @post-created="handleNewPost" />
    </div>
  </header>
</template>

<script>
import { useRouter } from "vue-router";
import PostForm from "./PostForm.vue";
import Navigation from "./Navigation.vue";

export default {
  name: "AppHeader",
  components: {
    PostForm,
    Navigation,
  },
  emits: ["post-created"],
  setup(props, { emit }) {
    const router = useRouter();

    const goToHome = () => {
      router.push("/");
    };

    const goToMyProfile = () => {
      router.push("/posts/current_user");
    };

    const handleNewPost = (post) => {
      emit("post-created", post);
    };

    return {
      goToHome,
      goToMyProfile,
      handleNewPost,
    };
  },
};
</script>

<style scoped>
.app-header {
  background: white;
  border-bottom: 1px solid #e1e8ed;
  padding: 15px 0;
  margin-bottom: 30px;
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-container {
  max-width: 600px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 15px 15px 15px;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 20px;
}

.logo {
  color: #1da1f2;
  font-size: 24px;
  margin: 0;
  cursor: pointer;
  transition: opacity 0.2s;
}

.logo:hover {
  opacity: 0.8;
}

.current-user {
  color: #657786;
  font-size: 14px;
  background: #f5f8fa;
  padding: 5px 10px;
  border-radius: 20px;
  cursor: pointer;
  transition: background 0.2s;
}

.current-user:hover {
  background: #e1e8ed;
}

.post-form-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 0 15px;
}
</style>
