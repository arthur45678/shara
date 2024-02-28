var gulp = require("gulp");
var plugins = require('gulp-load-plugins')();
var htmlmin = require('gulp-htmlmin');

var paths = require('./public/gulp/paths');

var getTask = function (task) {

    return require('./public/gulp/tasks/' + task)(gulp, plugins, paths, htmlmin);
};




//gulp.task('scss', getTask('scss'));

gulp.task('scripts', getTask('scripts'));

gulp.task('css', getTask('css'));

gulp.task('html', getTask('html'));

gulp.task("default", ['scripts','css', 'html']);