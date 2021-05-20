var gulp = require('gulp'),
    rename = require('gulp-rename'),
    terser = require('gulp-terser');

var paths = {
    scripts: {
        src: 'assets/js/**/*.js',
        dest: 'src/resources/js'
    }
};

function scripts() {
    return gulp.src(paths.scripts.src)
        .pipe(terser())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(paths.scripts.dest));
}

function watch() {
    gulp.watch(paths.scripts.src, scripts);
}

var build = gulp.parallel(scripts);

exports.watch = watch;
exports.build = build;

exports.default = build;
