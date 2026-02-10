import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import UserPosts from "../views/UserPosts.vue";
import HashtagPosts from "../views/HashtagPosts.vue";

const routes = [
  { path: "/", component: Home },
  { path: "/:username", component: UserPosts, props: true },
  { path: "/hash/:hashtag", component: HashtagPosts, props: true },
];

export default createRouter({
  history: createWebHistory(),
  routes,
});
