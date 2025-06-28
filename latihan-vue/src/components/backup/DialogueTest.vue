<template>
  <div class="dialog-content">
    <button class="dialog-close" @click="close">×</button>
    <h2>Dialog Title</h2>
    <p>This is the content of the dialog.</p>
    <button @click="close">Close</button>
  </div>
</template>

<script setup>
import { defineEmits } from "vue";

const emit = defineEmits(["close"]);

const close = () => {
  emit("close");
};
</script>

<style scoped>
.dialog-content {
  background: white;
  padding: 20px;
  border-radius: 5px;
  position: relative;
}

.dialog-close {
  position: absolute;
  top: 10px;
  right: 10px;
  background: none;
  border: none;
  font-size: 18px;
  cursor: pointer;
}
</style>
