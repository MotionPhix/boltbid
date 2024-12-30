<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import {Label} from '@/Components/ui/label'
import {Input} from '@/Components/ui/input'
import {Button} from "@/Components/ui/button";
import {Separator} from "@/Components/ui/separator";
import InputError from "@/Components/InputError.vue";
import {addMinutes, differenceInSeconds} from 'date-fns';
import {onMounted, ref} from "vue";

const countdown = ref()
const expiresAt = ref(addMinutes(new Date(), 10));
const otpExpired = ref(false);

const startCountdown = () => {
  const updateCountdown = () => {
    if (expiresAt.value) {
      const now = new Date();
      const secondsLeft = differenceInSeconds(expiresAt.value, now);
      if (secondsLeft <= 0) {
        otpExpired.value = true;
        countdown.value = 'OTP has expired.';
        localStorage.setItem('otpExpired', 'true');
      } else {
        const minutes = Math.floor(secondsLeft / 60);
        const seconds = secondsLeft % 60;
        countdown.value = `${minutes}:${seconds.toString().padStart(2, '0')}`;
      }
    }
  };
  updateCountdown();
  setInterval(updateCountdown, 1000);
};

const form = useForm({
  email: '',
  otp: '',
});

const verifyOtp = () => {
  form.post(route('otp.verify'), {
    onSuccess: () => {
      localStorage.removeItem('otpExpired');
    },
  });
};

onMounted(() => {
  if (localStorage.getItem('otpExpired') === 'true') {
    otpExpired.value = true;
    countdown.value = 'OTP has expired.';
  } else {
    startCountdown();
  }
});
</script>

<template>
  <Head title="Email Verification"/>

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo/>
    </template>

    <div>
      <h1 v-if="countdown">
        Verify OTP in {{ countdown }}
      </h1>

      <h1 v-else>
        OTP has expired
      </h1>

      <div v-if="countdown" class="font-medium text-sm text-green-600 dark:text-green-400">
        Enter the email address the OTP was sent to and the OTP.
      </div>

      <Separator class="my-6"/>

      <form @submit.prevent="verifyOtp" class="grid grid-cols-1 gap-6">
        <div class="grid grid-cols-1 gap-2">
          <Label for="email">Email</Label>
          <Input
            type="email"
            v-model="form.email"
            placeholder="Enter your email address"
          />

          <InputError :message="form.errors.email"/>
        </div>

        <div class="grid grid-cols-1 gap-2">
          <Label for="otp">OTP</Label>
          <Input
            type="text"
            placeholder="Enter the OTP you received"
            v-model="form.otp"/>

          <InputError :message="form.errors.otp?.otp"/>
        </div>

        <Button
          type="submit"
          :disabled="form.processing">
          Verify
        </Button>
      </form>
    </div>
  </AuthenticationCard>
</template>
