Vue.component('team-settings', {
    /**
     * The component's data.
     */
    data() {
        return {
            teams: [],
        };
    },


    /**
     * Prepare the component.
     */
    mounted() {
        this.getTeams();
    },


    /**
     * The component has been created by Vue.
     */
    created() {
        var self = this;

        this.$on('updateTeams', function(){
            self.getTeams();
        });
    },


    methods: {
        /**
         * Get the teams.
         */
        getTeams() {
            axios.get('/settings/teams')
                .then(response => this.teams = response.data.data);
        }
    }
});
