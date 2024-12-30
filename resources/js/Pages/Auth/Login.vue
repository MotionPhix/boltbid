<script setup lang="ts">
import {Head, router, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {Separator} from '@/Components/ui/separator'
import {Button} from '@/Components/ui/button'
import {Loader2} from "lucide-vue-next";

defineProps<{
  status: string
}>();

const form = useForm({
  email: '',
  remember: false,
});

const submit = () => {
  form.transform(data => ({
    ...data,
    remember: form.remember ? 'on' : '',
  })).post(route('otp.request'), {
    onSuccess: () => {
      router.visit(route('otp.seek'))
    },

    // (route('verify.otp'))
  });
};
</script>

<template>
  <Head title="Log in"/>

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo/>
    </template>

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <Button variant="outline" as-child class="w-full">
        <a
          :href="route('auth.google')">
          Login with Google
        </a>
      </Button>

      <Separator class="my-6" label="Or"/>

      <div>
        <InputLabel for="email" value="Email"/>
        <TextInput
          id="email"
          v-model="form.email"
          type="email"
          class="mt-1 block w-full"
          placeholder="Enter your email address"
          autofocus
        />
        <InputError class="mt-2" :message="form.errors.email"/>
      </div>

      <div class="block mt-4">
        <label class="flex items-center">
          <Checkbox v-model:checked="form.remember" name="remember"/>
          <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
        </label>
      </div>

      <div class="flex items-center justify-end mt-4">

        <Button
          type="submit"
          class="ms-4" :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing">
          <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin"/>
          Log in
        </Button>

      </div>
    </form>
  </AuthenticationCard>
</template>
