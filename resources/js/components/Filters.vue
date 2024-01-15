<template>
  <div>
    <div class="flex gap-2">
      <select @change="addStrengthFilter" v-model="strength" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        <option disabled value="">Select strengths</option>
        <option v-for="(strength, index) in computedStrengths" :key="'select-strength-' + index" :value="strength">{{strength}}</option>
      </select>
      <select @change="addSkillFilter" v-model="skill" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        <option disabled value="">Select skills</option>
        <option v-for="(skill, index) in computedSkills" :key="'select-skill-' + index" :value="skill">{{skill}}</option>
      </select>
    </div>
    <div class="flex flex-col gap-1">
      <div v-if="myFilters.strengths.length" class="py-2 flex">
        <span class="mr-4">Strengths: </span>
        <span
            v-for="strength in myFilters.strengths"
            class="inline-block rounded-full bg-green-200 px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
        >
        {{strength}}
        <span @click="removeStrengthFilter(strength)" class="ml-2 cursor-pointer">x</span>
      </span>
      </div>
      <div v-if="myFilters.skills.length" class="py-2 flex">
        <span class="mr-4">Skills: </span>
        <span
            v-for="(skill, index) in myFilters.skills"
            :key="'skill-' + index"
            class="inline-block rounded-full bg-red-200 px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
        >
        {{skill}}
        <span @click="removeSkillFilter(skill)" class="ml-2 cursor-pointer">x</span>
      </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['strengths', 'skills', 'filters'],
  emits: ['setFilters'],
  data() {
    return {
      strength: '',
      skill: '',
      myFilters: {
        strengths: this.filters.strengths ?? [],
        skills: this.filters.skills ?? [],
      },
    }
  },
  computed: {
    computedStrengths: function () {
      return this.strengths.filter((strength) => !this.myFilters.strengths.includes(strength))
    },
    computedSkills: function () {
      return this.skills.filter((strength) => !this.myFilters.skills.includes(strength))
    },
  },
  methods: {
    addStrengthFilter: function (e) {
      if (this.strength !== '') {
        this.myFilters.strengths.push(this.strength)
      }
      this.strength = '';

    },
    removeStrengthFilter: function (strengthToRemove) {
      this.myFilters.strengths = this.myFilters.strengths.filter((strength) => strength !== strengthToRemove);
    },
    addSkillFilter: function (e) {
      if (this.skill !== '') {
        this.myFilters.skills.push(this.skill)
      }
      this.skill = '';
    },
    removeSkillFilter: function (skillToRemove) {
      this.myFilters.skills = this.myFilters.skills.filter((strength) => strength !== skillToRemove);
    }
  },
  watch: {
    myFilters: {
      handler() {
        this.$emit('setFilters', this.myFilters);
      },
      deep: true,
    }
  },
}
</script>