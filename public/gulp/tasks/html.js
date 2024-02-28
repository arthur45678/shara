module.exports = function (gulp, plugins, paths, htmlmin) {

    return function () {

        gulp.src(paths.html)
	    .pipe(htmlmin({collapseWhitespace: true}))
	    .pipe(gulp.dest('public/dist/assets/html'));

    };
};