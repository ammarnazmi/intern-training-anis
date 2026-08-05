Alpine.data('ListPage', () =>
    window.AlpineComponents.ListPage({
        data: PageData.users,

        defaultParams: {
            page: 1,
            status: 'all',
            search_column: {
                depends: 'search_value',
                value: 'name',
            },
            search_value: '',
            sort: '-id',
        },

        activate(user) {
            abmodal({
                message: __('Do you want to activate this user?') + '<br>' + eh(user.name),
                onEscape: true,
                buttons: {
                    yes: {
                        label: __('Yes'),
                        className: 'btn-primary',
                        callback: () => {
                            Helper.showPageSpinner();

                            axios
                                .put(zroute('admin.users.activate', user.id))
                                .then((response) => {
                                    user.status = UserStatus.Active;

                                    Helper.showNotificationSuccess(__('User :name has been activated successfully.', { name: eh(user.name) }));
                                })
                                .catch((error) => {
                                    Helper.showNotificationError(error.response.data.message);
                                })
                                .then(() => {
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

        ban(user) {
            abmodal({
                message: __('Do you want to ban this user?') + '<br>' + eh(user.name),
                onEscape: true,
                buttons: {
                    yes: {
                        label: __('Yes'),
                        className: 'btn-warning',
                        callback: () => {
                            Helper.showPageSpinner();

                            axios
                                .put(zroute('admin.users.ban', user.id))
                                .then((response) => {
                                    user.status = UserStatus.Banned;

                                    Helper.showNotificationSuccess(__('User :name has been banned successfully.', { name: eh(user.name) }));
                                })
                                .catch((error) => {
                                    Helper.showNotificationError(error.response.data.message);
                                })
                                .then(() => {
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

        unban(user) {
            abmodal({
                message: __('Do you want to unban this user?') + '<br>' + eh(user.name),
                onEscape: true,
                buttons: {
                    yes: {
                        label: __('Yes'),
                        className: 'btn-primary',
                        callback: () => {
                            Helper.showPageSpinner();

                            axios
                                .put(zroute('admin.users.unban', user.id))
                                .then((response) => {
                                    user.status = UserStatus.Active;

                                    Helper.showNotificationSuccess(__('User :name has been unbanned successfully.', { name: eh(user.name) }));
                                })
                                .catch((error) => {
                                    Helper.showNotificationError(error.response.data.message);
                                })
                                .then(() => {
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

        remove(user) {
            abmodal({
                message: __('Do you want to delete this user?') + '<br>' + eh(user.name),
                onEscape: true,
                buttons: {
                    yes: {
                        label: __('Yes'),
                        className: 'btn-danger',
                        callback: () => {
                            this.deleteItem(user, (data) => {
                                Helper.showNotificationSuccess(__('User :name has been deleted successfully.', { name: eh(user.name) }));
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
