Vue.component('create-team', {
    props: [],


    /**
     * The component's data.
     */
    data() {
        return {
            form: new Form({
                name: '',
                slug: '',
            })
        };
    },


    watch: {
        /**
         * Watch the name for changes.
         */
        'form.name': function (val, oldVal) {
            if (this.form.slug == '' || this.form.slug == oldVal.toLowerCase().replace(/[\s\W-]+/g, '-')) {
                this.form.slug = val.toLowerCase().replace(/[\s\W-]+/g, '-');
            }
        }
    },


    methods: {
        /**
         * Create a new team.
         */
        createTeam() {
            this.form.startProcessing();

            axios.post('/settings/teams', this.form)
                .then(() => {
                    this.form.finishProcessing();
                    this.resetForm();
                    this.$parent.$emit('updateTeams');
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
            this.form.slug = '';
        }
    }
});
