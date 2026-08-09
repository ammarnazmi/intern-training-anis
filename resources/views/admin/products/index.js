Alpine.data('ListPage', () =>
    window.AlpineComponents.ListPage({
        data: PageData.products,

        defaultParams: {
            page: 1,
            sort: '-id',
            search_column: {
                 depends: 'search_value',
                 value: 'name',
             },
            search_value: '',
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
                            this.deleteItem(product, () => {
                                Helper.showNotificationSuccess(
                                    __('Product :name has been deleted successfully.', {
                                        name: eh(product.name)
                                    })
                                );
                            });
                        }
                    },
                    no: {
                        label: __('No'),
                    },
                },
            });
        },
    })
);
