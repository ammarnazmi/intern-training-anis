const path = require('path');
const rspack = require('@rspack/core');
const dotenvx = require('@dotenvx/dotenvx');
const { styleText } = require('node:util');
const { defineConfig } = require('@rspack/cli');
const { resolveLoader } = require('@hafizuddin/rspack-plugins');

module.exports = defineConfig((env, argv) => {
    dotenvx.config({ processEnv: env, quiet: true });

    const mode = argv.mode ?? 'production';
    const isProduction = mode === 'production';
    const cache = JSON.parse(env.cache ?? true);
    const targets = env.targets ?? 'last 1 year';
    const hashLength = JSON.parse(env['hash-length'] ?? 16);
    const chunkFilename = (type) => `${type}/${isProduction ? 'c' : ''}[id]${isProduction ? `.h[contenthash:${hashLength}]` : ''}.${type}`;

    console.info(styleText('blueBright', 'Mode:'), mode);
    console.info(styleText('blueBright', 'Cache:'), cache);
    console.info(styleText('blueBright', 'Targets:'), targets);

    return {
        mode,
        cache: cache ? { type: 'persistent' } : false,
        incremental: isProduction ? 'advance-silent' : 'advance',
        stats: {
            preset: 'normal',
            assets: true,
            cachedAssets: true,
            entrypoints: true,
        },
        ignoreWarnings: [
            (warning) => (warning.message.includes('ModuleWarning') || warning.message.includes('Module Warning')) && warning.message.includes('sass-loader'),
        ],
        entry: {
            app: ['./resources/sass/app.scss', './resources/js/app.js'],
        },
        output: {
            path: path.resolve(__dirname, 'public'),
            filename: 'js/[name].js',
            cssFilename: 'css/[name].css',
            chunkFilename: chunkFilename('js'),
            cssChunkFilename: chunkFilename('css'),
        },
        resolve: {
            alias: {
                vendor: path.resolve(__dirname, 'vendor'),
            },
        },
        performance: {
            hints: false,
        },
        plugins: [
            new rspack.DefinePlugin({
                IS_BOOTSTRAP4: JSON.stringify(false),
                IS_BOOTSTRAP5: JSON.stringify(true),
                ...Object.fromEntries(
                    Object.entries(env)
                        .filter(([key]) => key.startsWith('JS_'))
                        .map(([key, value]) => ['import.meta.env.' + key, JSON.stringify(value)])
                ),
            }),
            new rspack.ProgressPlugin({
                activeModules: false,
                entries: true,
                modules: true,
                modulesCount: 5000,
                profile: false,
                dependencies: true,
                dependenciesCount: 10000,
                percentBy: null,
            }),
        ],
        optimization: {
            minimize: isProduction,
            minimizer: [
                new rspack.SwcJsMinimizerRspackPlugin({
                    minimizerOptions: {
                        compress: {
                            passes: 3,
                        },
                        format: {
                            comments: 'some',
                        },
                    },
                }),
                new rspack.LightningCssMinimizerRspackPlugin({
                    minimizerOptions: {
                        targets,
                    },
                }),
            ],
        },
        experiments: {
            nativeWatcher: true,
        },
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: [
                        {
                            loader: 'builtin:swc-loader',
                            options: {
                                env: {
                                    targets,
                                },
                            },
                        },
                        {
                            loader: isProduction ? resolveLoader('minify-tagged-html-template') : resolveLoader('noop'),
                            options: {
                                tagFunctionNames: ['Helper.minifyHtml', 'minifyHtml'],
                                removeTagFunction: true,
                            },
                        },
                    ],
                },
                {
                    test: /\.scss$/,
                    type: 'css',
                    use: [
                        {
                            loader: isProduction ? resolveLoader('noop') : 'builtin:lightningcss-loader',
                            options: {
                                targets,
                            },
                        },
                        {
                            loader: 'sass-loader',
                            options: {
                                api: 'modern-compiler',
                                implementation: require.resolve('sass-embedded'),
                                sassOptions: {
                                    style: 'expanded',
                                },
                            },
                        },
                    ],
                },
            ],
        },
    };
});
