module.exports = function (grunt) {

    grunt.initConfig({

        // Clean the previous build
        clean: [
            'web/build'
        ],

        // Treat the react files (.jsx to .js)
        browserify: {
            options: {
                debug: true,
                extensions: ['.jsx'],
                transform: ['reactify']
            },
            reactApp: {
                src: 'src/AppBundle/Resources/jsx/app.jsx',
                dest: 'web/build/js/app.js'
            }
        },

        // Basic copy into web/build
        copy: {
            bootstrap: {
                files: [{
                    expand: true,
                    cwd: 'bower_components/bootstrap/dist',
                    src: '**/*',
                    dest: 'web/build/vendor/bootstrap/'
                }]
            },
            jquery: {
                files: [{
                    expand: true,
                    cwd: 'bower_components/jquery/dist',
                    src: '**/*',
                    dest: 'web/build/vendor/jquery/'
                }]
            },
            underscore: {
                files: [{
                    expand: true,
                    cwd: 'bower_components/underscore',
                    src: '**/*',
                    dest: 'web/build/vendor/underscore/'
                }]
            }
        },

        // Css compression
        cssmin: {
            target: {
                files: [{
                    expand: true,
                    cwd: 'src/AppBundle/Resources/css',
                    src: ['*.css', '!*.min.css'],
                    dest: 'web/build/css',
                    ext: '.min.css'
                }]
            }
        },

        // Javascript compression
        uglify: {
            target: {
                files: {
                    'web/build/js/main.min.js': [
                        'web/build/vendor/jquery/jquery.js',
                        'web/build/vendor/bootstrap/js/bootstrap.js',
                        'web/build/vendor/underscore/underscore.js',
                        'web/build/js/app.js'
                    ]
                }
            }
        },

        // Rebuild when some files are modified
        watch: {
            css: {
                files: 'src/AppBundle/Resources/**/*.css',
                tasks: ['cssmin'],
                options: {
                    interrupt: true
                }
            },
            reactApp: {
                files: 'src/AppBundle/Resources/**/*.jsx',
                tasks: ['browserify', 'uglify'],
                options: {
                    interrupt: true
                }
            }
        },

        // Shortcuts for some frequently used commands
        shell: {
            installSymfonyVendor: {
                command: 'php composer.phar selfupdate && php composer.phar install --prefer-dist --optimize-autoloader'
            },
            installNodeModules: {
                command: 'npm install'
            },
            installBowerComponents: {
                command: 'bower install'
            },
            runSymfonyServer: {
                command: 'php app/console server:run'
            }
        }

    });

    // Install vendors and tools
    grunt.registerTask('install', [
        'shell:installSymfonyVendor',
        'shell:installNodeModules',
        'shell:installBowerComponents'
    ]);

    // Prepare a build
    grunt.registerTask('dump', [
        'clean',
        'copy',
        'browserify',
        'cssmin',
        'uglify'
    ]);

    // Start a symfony server
    grunt.registerTask('server', [
        'shell:runSymfonyServer'
    ]);

    // Used grunt plugins
    grunt.loadNpmTasks('grunt-browserify');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-shell');

};