var gulp = require('gulp'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat');

gulp.task('default', ['css', 'js', 'fonts']);

gulp.task('css', function () {
    gulp.src(['source/scss/bootstrap.scss', 'source/scss/style.scss'])
        .pipe(concat('style.scss'))
        .pipe(sass())
        .pipe(gulp.dest('source/css/'));
});

gulp.task('js', function () {
    gulp.src([
            'node_modules/jquery/dist/jquery.min.js',
            'node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js'
        ])
        .pipe(gulp.dest('source/js/'));
});

gulp.task('fonts', function () {
    gulp.src('node_modules/bootstrap-sass/assets/fonts/bootstrap/*')
        .pipe(gulp.dest('source/fonts/'));
});

gulp.task('watch', function () {
    gulp.watch('source/scss/**/*.scss', ['css']);
    gulp.watch('source/js/*.js', ['js']);
});
