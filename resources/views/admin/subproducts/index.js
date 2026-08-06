Alpine.data('SubproductListPage', () =>
    window.AlpineComponents.ListPage({
        data: PageData.subproducts,

        defaultParams: {
            page: 1,
            sort: '-id',
        },

        remove(subproduct) {
            abmodal({
                message: __('Do you want to delete this subproduct?') + '<br>' + eh(subproduct.name),
                onEscape: true,
                buttons: {
                    yes: {
                        label: __('Yes'),
                        className: 'btn-danger',
                        callback: () => {
                            Helper.showPageSpinner();

                            axios
                                .delete('/admin/subproducts/' + subproduct.id)
                                .then((response) => {
                                    const index = this.data.data.findIndex(item => item.id === subproduct.id);
                                    if (index !== -1) {
                                        this.data.data.splice(index, 1);
                                    }
                                    Helper.showNotificationSuccess(__('Subproduct :name has been deleted successfully.', { name: eh(subproduct.name) }));
                                })
                                .catch((error) => {
                                    Helper.showNotificationError(error.response?.data?.message || __('Failed to delete subproduct.'));
                                })
                                .finally(() => {
                                    Helper.removePageSpinner();
                                });
                        },
                    },
                    no: {
                        label: __('No'),
                    },
                },
            });
        },
    })
);
