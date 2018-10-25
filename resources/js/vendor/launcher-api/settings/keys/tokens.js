module.exports = {
    props: ['keys'],


    /**
     * The component's data.
     */
    data() {
        return {
            deletingKey: null,
        }
    },


    methods: {
        /**
         * Get user confirmation that the key should be deleted.
         */
        approveKeyDelete(key) {
            this.deletingKey = key;

            swal({
                title: `Delete Key ${key.name}?`,
                text: 'Are you sure you want to delete this key? If deleted, requests that attempt to authenticate using this token will no longer be accepted.',
                icon: 'warning',
                dangerMode: true,
                buttons: [true, "Delete Key"],
            })
            .then((willDelete) => {
                if (willDelete) {
                    this.deleteKey();
                }
            })
        },


        /**
         * Delete the specified key.
         */
        deleteKey() {
            axios.delete(`/settings/keys/tokens/${this.deletingKey.id}`)
                .then(() => {
                    this.$parent.$emit('updateKeys');
                });
        }
    }
};
