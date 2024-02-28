module.exports = function (gulp, plugins, paths) {

    return function () {

        gulp.src(paths.css)
            .pipe(plugins.cssmin())
            .pipe(plugins.concat("all.css"))
            .pipe(gulp.dest('public/dist/assets/css'));

    };
};