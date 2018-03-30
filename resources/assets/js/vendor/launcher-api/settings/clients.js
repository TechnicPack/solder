module.exports = {
    /**
     * The component's data.
     */
    data() {
        return {
            clients: [],
        };
    },


    /**
     * Prepare the component.
     */
    mounted() {
        this.getClients();
    },


    /**
     * The component has been created by Vue.
     */
    created() {
        var self = this;

        this.$on('updateClients', function(){
            self.getClients();
        });
    },


    methods: {
        /**
         * Get the client tokens.
         */
        getClients() {
            axios.get('/settings/clients/tokens')
                .then(response => this.clients = response.data.data);
        }
    }
};
