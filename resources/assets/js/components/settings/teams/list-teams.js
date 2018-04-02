Vue.component('list-teams', {
    props: ['teams'],


    /**
     * The component's data.
     */
    data() {
        return {
            deletingTeam: null,
        }
    },


    methods: {
        updateTeamName(team) {
            swal({
                text: 'Update team name.',
                content: {
                    element: "input",
                    attributes: {
                        value: team.name,
                    },
                },
                button: {
                    text: "Update",
                    closeModal: false,
                },
            })
            .then(name => {
                axios.patch(`/settings/teams/${team.id}/name`, {name: name})
                    .then(() => {
                        this.$parent.$emit('updateTeams');
                        swal.stopLoading();
                        swal.close();
                    })
                    .catch(function (error) {
                        swal("Oh noes!", error.response.data.message, "error");
                    });
            });
        },


        /**
         * Get user confirmation that the client should be deleted.
         */
        approveTeamDelete(team) {
            this.deletingTeam = team;

            swal({
                title: `Delete Team ${team.name}?`,
                text: 'Are you sure you want to delete this team?',
                icon: 'warning',
                dangerMode: true,
                buttons: [true, "Delete Team"],
            })
                .then((willDelete) => {
                    if (willDelete) {
                        this.deleteTeam();
                    }
                })
        },


        /**
         * Delete the specified team.
         */
        deleteTeam() {
            axios.delete(`/settings/teams/${this.deletingTeam.id}`)
                .then(() => {
                    this.$parent.$emit('updateTeams');
                });
        }
    }
});
