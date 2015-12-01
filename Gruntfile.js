/*global module:false*/
module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        // Task configuration.
        less: {
            development: {
                options: {
                    compress: true
                },
                files: {
                    './www/templates/html/css/player.css': './www/templates/html/less/player.less'
                }
            }
        },
        watch: {
            files: ['./www/templates/html/less/*'],
            tasks: ['less'],
            options: {
                event: ['changed', 'added', 'deleted']
            }
        }
    });

    // These plugins provide necessary tasks.
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task.
    grunt.registerTask('default', ['less']);
};
