Alpine.data('CountsDisplay', () => ({
    loaded: false,

    data: {
        users_count: 0,
    },

    init() {
        axios.get(zroute('admin.main.counts')).then((response) => {
            this.data = response.data;
            this.loaded = true;
        });
    },
}));

Alpine.data('OnlineUsers', () => ({
    eventStream: null,
    users: [],
    intervalId: 0,

    init() {
        // Format user data
        const formatData = (users) => {
            for (const user of users) {
                user.ago = Math.round((Date.now() - new Date(user.last_online_at)) / 1000);
            }

            return users.filter((user) => user.ago <= 600);
        };

        // Get online users
        this.eventStream = useEventStream({
            url: zroute('admin.main.online_users'),
            reopenAfterCompleted: true,
            closeAfterHiddenFor: 10,
            onMessage: (event) => {
                this.users = formatData(JSON.parse(event.data));
            },
        });

        // Update seconds
        this.intervalId = window.setInterval(() => (document.hidden ? null : (this.users = formatData(this.users))), 1000);
    },

    destroy() {
        this.eventStream?.close();
        window.clearInterval(this.intervalId);
    },
}));
