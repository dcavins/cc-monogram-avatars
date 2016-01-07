'use strict';
module.exports = function(grunt) {

	// load all grunt tasks matching the `grunt-*` pattern
	require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// Watch for changes and trigger less, jshint, uglify and livereload
		watch: {
			options: {
				livereload: true
			},
			styles: {
				files: ['public/css/*.less'],
				tasks: ['less:cleancss', 'autoprefixer']
			}
		},

		less: {
			cleancss: {
				options: {
					cleancss: true
				},
				files: {
					'public/css/public.css': 'public/css/public.less'
				}
			}
		},

		// Autoprefixer handles vendor prefixes in css rules
		autoprefixer: {
			options: {
				map: true
			},
			files: {
				expand: true,
				flatten: true,
				src: 'public/css/*.css',
				dest: 'public/css/' //replaces source file
			}
		},

		// Image optimization
		imagemin: {
			dist: {
				options: {
					optimizationLevel: 7,
					progressive: true,
					interlaced: true
				},
				files: [{
					expand: true,
					cwd: 'public/img/',
					src: ['**/*.{png,jpg,gif}'],
					dest: 'public/img/'
				}]
			}
		}

	});

	// Register tasks
	// Typical run, cleans up css and js, starts a watch task.
	grunt.registerTask('default', ['less:cleancss', 'autoprefixer', 'watch']);

	// Before releasing a build, do above plus minimize all images.
	grunt.registerTask('build', ['less:cleancss', 'autoprefixer', 'imagemin']);

};