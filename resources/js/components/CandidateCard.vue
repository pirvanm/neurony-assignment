<script>
export default {
  props:['candidate', 'desiredStrength', 'desiredSkills', 'company'],
  emits: ['contact', 'hire'],
  methods: {
    hasDesiredStrength: function (strength) {
      return this.desiredStrength.includes(strength);
    },
    hasDesiredSkill: function (skill) {
      return this.desiredSkills.includes(skill);
    },
    contactCandidate: function () {
      this.$emit('contact', this.candidate);
    },
    hireCandidate: function () {
      this.$emit('hire', this.candidate);
    }
  },
  computed: {
    contacted: function () {
      return this.candidate.companies.length === 1 && this.candidate.companies[0].status === 'CONTACTED';
    },
    hired: function () {
      return this.candidate.companies.length === 1 && this.candidate.companies[0].status === 'HIRED';
    }
  }
}
</script>

<template>
  <div class="rounded overflow-hidden shadow-lg">
    <img class="w-full" src="/avatar.png" alt="">
    <div class="px-6 py-4"><div class="font-bold text-xl mb-2">{{candidate.name}}</div>
      <p class="text-gray-700 text-base">{{candidate.description}}</p>
    </div>
    <div class="px-6 pt-4 pb-2">
      <span
          v-for="strength in JSON.parse(candidate.strengths)"
          class="inline-block rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
          :class="hasDesiredStrength(strength) ? 'bg-green-200' : 'bg-gray-200'"
      >
        {{strength}}
      </span>
    </div>
    <div class="px-6 pb-2">
      <span
          v-for="skill in JSON.parse(candidate.soft_skills)"
          class="inline-block rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
          :class="hasDesiredSkill(skill) ? 'bg-red-200' : 'bg-gray-200'"
      >
        {{skill}}
      </span>
    </div>
    <div v-if="company" class="p-6 float-right">
      <button @click="contactCandidate" v-if="!contacted && !hired" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">Contact</button>
      <button @click="hireCandidate" v-if="company && contacted && !hired" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 hover:bg-teal-100 rounded shadow">Hire</button>
    </div>
  </div>
</template>

<style scoped>

</style>