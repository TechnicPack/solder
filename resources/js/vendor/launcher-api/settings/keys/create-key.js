module.exports = {
    props: [],


    /**
     * The component's data.
     */
    data() {
        return {
            form: new Form({
                name: '',
                token: '',
            })
        };
    },


    methods: {
        /**
         * Create a new key token.
         */
        createKey() {
            this.form.startProcessing();

            axios.post('/settings/keys/tokens', this.form)
                .then(() => {
                    this.form.finishProcessing();
                    this.resetForm();
                    this.$parent.$emit('updateKeys');
                })
                .catch(errors => {
                    this.form.setErrors(errors.response.data.errors);
                });
        },


        /**
         * Reset the form
         */
        resetForm() {
            this.form.name = '';
            this.form.token = '';
        }
    }
};
