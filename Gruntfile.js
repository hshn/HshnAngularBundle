module.exports = function (grunt) {

    grunt.initConfig({
        phpunit: {
            classes: {
                dir: 'Tests'
            },
            options: {
                bin: './bin/phpunit',
                colors: true,
                followOutput: true
            }
        },
        watch: {
            phpunit: {
                tasks: ['phpunit'],
                files: [
                    'Tests/**/*Test.php'
                ],
                options: {
                    nospawn: true
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-phpunit');

    grunt.registerTask('test:phpunit', ['phpunit']);
    grunt.registerTask('test', ['test:phpunit']);

    grunt.event.on('watch', function (action, filepath) {
        grunt.config(['phpunit', 'classes', 'dir'], filepath);
    });
};
