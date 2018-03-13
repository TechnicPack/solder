module.exports = {
    props: [],


    /**
     * The component's data.
     */
    data() {
        return {
            form: new Form({
                title: '',
                token: '',
            })
        };
    },


    methods: {
        /**
         * Create a new client token.
         */
        createClient() {
            this.form.startProcessing();

            axios.post('/settings/clients/tokens', this.form)
                .then(() => {
                    this.form.finishProcessing();
                    this.resetForm();
                    this.$parent.$emit('updateClients');
                })
                .catch(errors => {
                    this.form.setErrors(errors.response.data.errors);
                });
        },


        /**
         * Reset the form
         */
        resetForm() {
            this.form.title = '';
            this.form.token = '';
        }
    }
};