var gulp = require('gulp');
var path = require('path');
var sass = require('gulp-sass')(require('sass'));
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
var open = require('gulp-open');

var Paths = {
  HERE: './',
  DIST: 'dist/',
  MANAGE_CSS: './public/assets/manage/css/',
  APP_CSS: './public/assets/app/css/',
  MANAGE_SCSS_TOOLKIT_SOURCES: './src/manage/scss/soft-ui-dashboard.scss',
  APP_SCSS_TOOLKIT_SOURCES: './src/app/scss/ui.scss',
  APP_SCSS_TOOLKIT_SOURCES_COMMON: './src/app/scss/common.scss',
  MANAGE_SCSS: './src/manage/scss/**/**',
  APP_SCSS: './src/app/scss/**/**',
};

// 관리용 SCSS 컴파일
gulp.task('compile-manage-scss', function() {
  return gulp.src(Paths.MANAGE_SCSS_TOOLKIT_SOURCES)
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(sourcemaps.write(Paths.HERE))
    .pipe(gulp.dest(Paths.MANAGE_CSS));
});

// 앱용 SCSS 컴파일
gulp.task('compile-app-scss', function() {
  return gulp.src(Paths.APP_SCSS_TOOLKIT_SOURCES)
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(sourcemaps.write(Paths.HERE))
    .pipe(gulp.dest(Paths.APP_CSS));
});

// 앱용 SCSS 컴파일
gulp.task('compile-app-common-scss', function() {
  return gulp.src(Paths.APP_SCSS_TOOLKIT_SOURCES_COMMON)
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(sourcemaps.write(Paths.HERE))
    .pipe(gulp.dest(Paths.APP_CSS));
});

// 관리용 및 앱용 SCSS 파일 감시
gulp.task('watch', function() {
  gulp.watch(Paths.MANAGE_SCSS, gulp.series('compile-manage-scss'));
  gulp.watch(Paths.APP_SCSS, gulp.series('compile-app-scss'));
});

gulp.task('open', function() {
  gulp.src('pages/dashboard.html')
    .pipe(open());
});

// 관리용과 앱용을 모두 빌드 및 감시
gulp.task('build-all', gulp.parallel('compile-manage-scss', 'compile-app-scss', 'watch'));

// 애플리케이션 실행 및 감시
gulp.task('open-app', gulp.series('build-all', 'open'));