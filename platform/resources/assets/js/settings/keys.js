module.exports = {
    /**
     * The component's data.
     */
    data() {
        return {
            keys: [],
        };
    },


    /**
     * Prepare the component.
     */
    mounted() {
        this.getKeys();
    },


    /**
     * The component has been created by Vue.
     */
    created() {
        var self = this;

        this.$on('updateKeys', function(){
            self.getKeys();
        });
    },


    methods: {
        /**
         * Get the client tokens.
         */
        getKeys() {
            axios.get('/settings/keys/tokens')
                .then(response => this.keys = response.data.data);
        }
    }
};