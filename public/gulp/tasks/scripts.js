module.exports = function (gulp, plugins, paths) {

    return function () {


        gulp.src(paths.js)

            .pipe(plugins.uglify())
            .pipe(plugins.concat("app.min.js"))
            .pipe(gulp.dest("public/dist/assets/js"));


        gulp.src(paths.customJs)

            .pipe(plugins.uglify())
            .pipe(plugins.concat("all.js"))
            .pipe(gulp.dest("public/dist/assets/js"));





    };
};
