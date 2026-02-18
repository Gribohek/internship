<template>
  <div class="post-form">
    <textarea
      v-model="content"
      placeholder="Напишите пост"
      maxlength="280"
      :disabled="loading"
      @keydown.ctrl.enter="submitPost"
      @keydown.meta.enter="submitPost"
    ></textarea>

    <div class="controls">
      <span :class="{ 'text-red': charsLeft <= 0 }"> {{ charsLeft }}/280 </span>
      <button @click="submitPost" :disabled="!canSubmit || loading" class="btn">
        {{ loading ? "Отправка..." : "Опубликовать" }}
      </button>
    </div>

    <div v-if="error" class="error-message">
      {{ error }}
    </div>
  </div>
</template>

<script>
import { ref, computed } from "vue";
import api from "../services/api";

export default {
  emits: ["post-submited"],
  setup(_, { emit }) {
    const content = ref("");
    const loading = ref(false);
    const error = ref(null);

    const charsLeft = computed(() => 280 - content.value.length);
    const canSubmit = computed(
      () => content.value.trim().length > 0 && content.value.length <= 280,
    );

    const submitPost = async () => {
      if (!canSubmit.value) return;

      loading.value = true;
      error.value = null;

      try {
        const response = await api.createPost(content.value);
        emit("post-submited", response);
        content.value = "";
      } catch (err) {
        error.value = "Не удалось опубликовать пост. Попробуйте снова.";
      } finally {
        loading.value = false;
      }
    };

    return { content, loading, charsLeft, canSubmit, submitPost, error };
  },
};
</script>

<style scoped>
.post-form {
  border: 1px solid #e1e8ed;
  padding: 15px;
  border-radius: 8px;
  background: white;
}

textarea {
  width: 100%;
  min-height: 100px;
  padding: 10px;
  border: 1px solid #e1e8ed;
  border-radius: 4px;
  font-size: 16px;
  resize: vertical;
  margin-bottom: 10px;
  font-family: inherit;
}

textarea:focus {
  outline: none;
  border-color: #1da1f2;
}

.controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.btn {
  background: #1da1f2;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 20px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.2s;
}

.btn:hover:not(:disabled) {
  background: #0d8bf0;
}

.btn:disabled {
  background: #9bd1f9;
  cursor: not-allowed;
}

.text-red {
  color: #e0245e;
}

.error-message {
  margin-top: 10px;
  padding: 8px;
  background: #ffebee;
  color: #c62828;
  border-radius: 4px;
  font-size: 14px;
}
</style>
