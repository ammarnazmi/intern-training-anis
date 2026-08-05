module.exports = {
    content: ['public/js/app.js', 'resources/views/**/*.js', 'storage/framework/views/*.php'],
    css: ['public/css/app.css'],
    safelist: {
        deep: [/badge/, /btn/, /modal/, /popopver/, /tooltip/],
    },
};
