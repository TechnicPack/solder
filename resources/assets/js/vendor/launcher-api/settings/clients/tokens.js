module.exports = {
    props: ['clients'],


    /**
     * The component's data.
     */
    data() {
        return {
            deletingClient: null,
        }
    },


    methods: {
        /**
         * Get user confirmation that the client should be deleted.
         */
        approveClientDelete(client) {
            this.deletingClient = client;

            swal({
                title: `Delete Client ${client.title}?`,
                text: 'Are you sure you want to delete this client? If deleted, requests that attempt to authenticate using this token will no longer be accepted.',
                icon: 'warning',
                dangerMode: true,
                buttons: [true, "Delete Client"],
            })
            .then((willDelete) => {
                if (willDelete) {
                    this.deleteClient();
                }
            })
        },


        /**
         * Delete the specified client.
         */
        deleteClient() {
            axios.delete(`/settings/clients/tokens/${this.deletingClient.id}`)
                .then(() => {
                    this.$parent.$emit('updateClients');
                });
        }
    }
};
