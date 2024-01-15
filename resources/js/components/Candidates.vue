<template>
    <div>
      <CompanyWalletCoins v-if="company" :coins="coins"/>
      <div class="p-10">
        <h1 class="text-4xl font-bold">Candidates</h1>
      </div>
        <div class="p-10 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5">
            <CandidateCard
                v-for="candidate in candidates"
                :key="candidate.name"
                :candidate="candidate"
                :desired-strength="desiredStrengths"
                :desired-skills="desiredSkills"
                :company="company"
            />
        </div>

      <MvpCandidates :candidates="mvpCandidates"/>
    </div>
</template>

<script>
import MvpCandidates from "./MvpCandidates.vue";
import CandidateCard from "./CandidateCard.vue";
import CompanyWalletCoins from "./CompanyWalletCoins.vue";

export default {
  components: {CompanyWalletCoins, CandidateCard, MvpCandidates},
  props:['candidates', 'mvpCandidates', 'user', 'strengths', 'skills'],
  data() {
    return {
      coins: this.user?.company?.wallet?.coins,
      desiredStrengths: [
          'Vue.js', 'Laravel', 'PHP', 'TailwindCSS'
      ],
      desiredSkills: [
        'Diplomacy', 'Team player', 'Leadership',
      ],
    }
  },
  computed: {
    company: function () {
      return this.user?.company
    },
  }
}
</script>
