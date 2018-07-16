const gulp = require('gulp');
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');
const sourcemaps = require('gulp-sourcemaps');
const pump = require('pump');

gulp.task('default', () =>
  pump([
    gulp.src('./lib/assets/js/*.js'),
    babel({presets: ['env']}),
    uglify(),
    sourcemaps.write('.'),
    gulp.dest('./lib/assets/js/dist')
  ])
);

gulp.task('watch', () =>{
  gulp.watch('./lib/assets/js/*.js', ['default']);
});