<template>
  <div class="bg-white py-24">
    <div class="mx-auto p-10">
      <div class="mx-auto max-w-2xl lg:mx-0">
        <span class="text-medium font-bold tracking-tight text-gray-900 sm:text-4xl">MVPs of the week</span>
      </div>
      <ul role="list" class="mx-auto mt-20 grid max-w-2xl grid-cols-2 gap-y-16 gap-x-8 text-center sm:grid-cols-3 md:grid-cols-4 lg:mx-0 lg:max-w-none lg:grid-cols-5 xl:grid-cols-6">
        <li v-for="candidate in candidates" :key="candidate.name">
          <img class="mx-auto h-24 w-24 rounded-full" src="/avatar.png" alt="" />
          <h3 class="mt-6 text-base font-semibold leading-7 tracking-tight text-gray-900">{{ candidate.name }}</h3>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: ['candidates'],
}
</script>