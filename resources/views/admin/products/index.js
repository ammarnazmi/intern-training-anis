Alpine.data('ListPage', () =>
    window.AlpineComponents.ListPage({
        data: PageData.products,

        defaultParams: {
            page: 1,
            sort: '-id',
        },

        remove(product) {
            abmodal({
                message: __('Do you want to delete this product?') + '<br>' + eh(product.name) + '<br><br><span class="text-danger">' + __('All related subproducts will also be deleted.') + '</span>',
                onEscape: true,
                buttons: {
                    yes: {
                        label: __('Yes'),
                        className: 'btn-danger',
                        callback: () => {
                            Helper.showPageSpinner();

                            axios
                                .delete(zroute('admin.products.destroy', product.id))
                                .then((response) => {
                                   
                                    const index = this.data.data.findIndex(item => item.id === product.id);
                                    if (index !== -1) {
                                        this.data.data.splice(index, 1);
                                    }

                                    Helper.showNotificationSuccess(__('Product :name has been deleted successfully.', { name: eh(product.name) }));
                                })
                                .catch((error) => {
                                    Helper.showNotificationError(error.response?.data?.message || __('Failed to delete product.'));
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
