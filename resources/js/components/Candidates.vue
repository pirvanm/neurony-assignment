<template>
    <div>
      <vue-topprogress ref="topProgress"></vue-topprogress>
      <CompanyWalletCoins v-if="company" :coins="coins"/>
      <div class="p-10">
        <h1 class="text-4xl font-bold">Candidates</h1>
      </div>
      <div class="px-10">
        <Filters
            :strengths="strengths"
            :skills="skills"
            :filters="myFilters"
            @setFilters="setFilters"
        />
      </div>
      <div class="p-10 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5">
          <CandidateCard
              v-for="candidate in candidatesList"
              :key="candidate.name"
              :candidate="candidate"
              :desired-strength="myFilters.strengths"
              :desired-skills="myFilters.skills"
              :company="company"
              @contact="contactCandidate"
              @hire="hireCandidate"
          />
      </div>

      <MvpCandidates :candidates="mvpCandidates"/>
      <FlashMessage :position="'right bottom'"/>
    </div>
</template>

<script>
import MvpCandidates from "./MvpCandidates.vue";
import CandidateCard from "./CandidateCard.vue";
import CompanyWalletCoins from "./CompanyWalletCoins.vue";
import Filters from "./Filters.vue";

export default {
  components: {Filters, CompanyWalletCoins, CandidateCard, MvpCandidates},
  props:['candidates', 'mvpCandidates', 'user', 'strengths', 'skills', 'filters'],
  data() {
    return {
      candidatesList: this.candidates,
      coins: this.user?.company?.wallet?.coins,
      myFilters: {
        strengths: this.filters.strengths ?? [],
        skills: this.filters.skills ?? [],
      },
    }
  },
  methods: {
    contactCandidate: function (candidate) {
      this.$refs.topProgress.start()

      axios.post(`/api/candidates/contact/${candidate.id}`)
          .then((response) => {
            const data = response.data;
            this.candidatesList = this.candidatesList.map((candidate) => {
              if (candidate.id === data.id) {
                return data;
              }

              return candidate;
            })

            this.coins -= 5;

            this.flashMessage.success({
              title: 'Candidate Contacted',
              message: 'You have successfully contacted the candidate!'
            })
          })
          .catch((error) => {
            this.flashMessage.error({
              title: 'Something went wrong!',
              message: error.response.data.message,
            })
          })
          .finally(() => {
            this.$refs.topProgress.done()
          })
    },
    hireCandidate: function (candidate) {
      this.$refs.topProgress.start()
      axios.patch(`/api/candidates/hire/${candidate.id}`)
          .then((response) => {
            const data = response.data;
            this.candidatesList = this.candidatesList.map((candidate) => {
              if (candidate.id === data.id) {
                return data;
              }

              return candidate;
            })

            this.coins += 5;

            this.flashMessage.success({
              title: 'Candidate Hired',
              message: 'You have successfully hired the candidate!'
            })
          })
          .catch((error) => {
            this.flashMessage.error({
              title: 'Something went wrong!',
              message: error.response.data.messsage,
            })
          })
          .finally(() => {
            this.$refs.topProgress.done()
          })
    },
    filterFetchCandidates: function () {
      this.$refs.topProgress.start()

      axios.get('/api/candidates/filter', {
        params: {
          ...this.myFilters,
        }
      })
          .then((response) => {
            this.candidatesList = response.data;
          })
          .catch((error) => {
            console.log(error)
          })
          .finally(() => {
            this.$refs.topProgress.done()
          })
    },
    setFilters: function (filters) {
      this.myFilters = filters;

      this.filterFetchCandidates();
      this.replaceUrl();
    },
    replaceUrl: function () {
      const location = window.location;
      const url = new URL(location.protocol + '//' + location.host + location.pathname);

      this.myFilters.strengths.forEach((strength, index) => {
        url.searchParams.set(`strengths[${index}]`, strength)
      })

      this.myFilters.skills.forEach((skill, index) => {
        url.searchParams.set(`skills[${index}]`, skill)
      })

      window.history.replaceState(null, null, url)
    }
  },
  computed: {
    company: function () {
      return this.user?.company
    },
  }
}
</script>
