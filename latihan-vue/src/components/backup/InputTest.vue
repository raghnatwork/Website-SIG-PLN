<!-- Input.vue -->
<template>
  <div>
    <select v-model="selectedFruit">
      <option v-for="fruit in fruits" :key="fruit" :value="fruit">
        {{ fruit }}
      </option>
    </select>
  </div>
</template>

<script setup>
import { ref, watch, defineEmits } from "vue";

// Mendefinisikan props
const props = defineProps({
  jawab: {
    type: String,
  },
});

// Mendefinisikan event yang akan dipancarkan
const emit = defineEmits(["update:modelValue"]);

// Daftar buah-buahan untuk dropdown
const fruits = ref(["Apple", "Banana", "Cherry", "Date"]);

// Mengikat nilai dropdown dengan props
const selectedFruit = ref(props.modelValue);

// Menangani perubahan pada dropdown
const handleChange = () => {
  emit("update:modelValue", selectedFruit.value);
};

// Mengawasi perubahan pada props
watch(
  () => props.modelValue,
  (newValue) => {
    selectedFruit.value = newValue;
  }
);
</script>
