<script setup>
import { computed, defineProps, ref } from 'vue'

const settings = ref({})

const hiddenGenresText = computed({
  get: () => settings.value?.hiddenGenres.join('\n') ?? '',
  set: () => settings.value.hiddenGenres = hiddenGenresText.value.split('\n')
})
const hiddenStudiosText = computed({
  get: () => settings.value?.hiddenStudios.join('\n') ?? '',
  set: () => settings.value.hiddenStudios = hiddenStudiosText.value.split('\n')
})

</script>

<template>
  <b-form @submit="onSubmit">
    <b-form-checkbox
        v-model="settings.babepediaIntegration"
        value="accepted"
        unchecked-value="not_accepted"
    >
      I accept the terms and use
    </b-form-checkbox>

    <b-form-group
        label="Hidden genres (separated by new line)"
    >
      <b-form-textarea
          v-model="hiddenGenresText"
          placeholder=""
          rows="3"
          max-rows="6"
      ></b-form-textarea>
    </b-form-group>

    <b-form-group
        label="Hidden studios (separated by new line)"
    >
      <b-form-textarea
          v-model="hiddenStudiosText"
          placeholder=""
          rows="3"
          max-rows="6"
      ></b-form-textarea>
    </b-form-group>



    <b-form-group
        label="Hidden studios (separated by new line)"
    >
      <b-form-textarea
          v-model="hiddenStudiousText"
          placeholder=""
          rows="3"
          max-rows="6"
      ></b-form-textarea>
    </b-form-group>

    <b-form-group id="input-group-2" label="Your Name:" label-for="input-2">
      <b-form-input
          id="input-2"
          v-model="form.name"
          placeholder="Enter name"
          required
      ></b-form-input>
    </b-form-group>

    <b-form-group id="input-group-3" label="Food:" label-for="input-3">
      <b-form-select
          id="input-3"
          v-model="form.food"
          :options="foods"
          required
      ></b-form-select>
    </b-form-group>

    <b-form-group id="input-group-4" v-slot="{ ariaDescribedby }">
      <b-form-checkbox-group
          v-model="form.checked"
          id="checkboxes-4"
          :aria-describedby="ariaDescribedby"
      >
        <b-form-checkbox value="me">Check me out</b-form-checkbox>
        <b-form-checkbox value="that">Check that out</b-form-checkbox>
      </b-form-checkbox-group>
    </b-form-group>

    <b-button type="submit" variant="primary">Submit</b-button>
    <b-button type="reset" variant="danger">Reset</b-button>
  </b-form>

    <b-form-textarea
        v-model="hiddenGenresText"
        placeholder="Enter something..."
        rows="3"
        max-rows="6"
    ></b-form-textarea>
    <pre class="mt-3 mb-0">{{ hiddenGenresText }}</pre>
</template>

<style scoped lang="scss"></style>
