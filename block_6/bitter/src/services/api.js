import axios from "axios";

const API_URL = "http://localhost:8000/api";

const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
  withCredentials: true,
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error("API Error:", error.response?.data || error.message);
    return Promise.reject(error);
  },
);

export default {
  async getFeed(page = 1) {
    const response = await api.get(`/feed?page=${page}`);
    return response.data;
  },

  async createPost(content) {
    const response = await api.post("/posts", { content });
    return response.data;
  },

  async getUserPosts(username) {
    const response = await api.get(`/posts/user/${username}`);
    return response.data;
  },

  async getHashtagPosts(hashtag, page = 1) {
    const response = await api.get(`/hashtag/${hashtag}?page=${page}`);
    return response.data;
  },

  async getUser(username) {
    const response = await api.get(`/users/${username}`);
    return response.data;
  },

  async follow(username) {
    const response = await api.post(`/users/${username}/follow`);
    return response.data;
  },

  async unfollow(username) {
    const response = await api.post(`/users/${username}/unfollow`);
    return response.data;
  },
};
