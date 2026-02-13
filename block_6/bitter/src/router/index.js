import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import UserPosts from "../views/UserPosts.vue";
import HashtagPosts from "../views/HashtagPosts.vue";

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home,
  },
  {
    path: "/posts/:username",
    name: "UserPosts",
    component: UserPosts,
    props: true,
  },
  {
    path: "/hash/:hashtag",
    name: "HashtagPosts",
    component: HashtagPosts,
    props: true,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
