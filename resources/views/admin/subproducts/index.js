Alpine.data('SubproductListPage', () =>
    window.AlpineComponents.ListPage({
        data: PageData.subproducts,
        product: PageData.product.id,

        defaultParams: {
            page: 1,
            sort: '-id',
            search_column: {
                depends: 'search_value',
                value: 'name',
             },
             search_value: '',
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
                            this.deleteItem(subproduct, () => {
                                Helper.showNotificationSuccess(
                                    __('Subproduct :name has been deleted successfully.', {
                                        name: eh(subproduct.name)
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
