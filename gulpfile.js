var gulp = require('gulp'),
    sass = require('gulp-sass'),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    concat = require('gulp-concat'),
    minifyCss = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    pngquant = require('imagemin-pngquant');

var css_files = [
    './public/css/bootstrap.min.css',
    './public/css/flag-icon.css',
    './public/css/jquery.datetimepicker.css',
    './public/css/jquery.jscrollpane.css',
    './public/css/jquery-ui-1.10.4.tooltip.css',
    './public/css/leaflet.css',
    './public/css/app.css'
];

var css_style = [
    './public/css/style.css'
];

var js_files = [
    './public/js/jquery.js',
    './public/js/bootstrap.min.js',
    './public/js/jquery-ui-1.10.4.tooltip.js',
    './public/js/jquery.cookie.js',
    './public/js/jquery.mousewheel.js',
    './public/js/jquery.jscrollpane.min.js',
    './public/js/select2.min.js',
    './public/js/jquery.datetimepicker.full.min.js',
    './public/js/main.js'
];


/**
 * Compile files from _scss
 */
gulp.task('sass', function () {
    return gulp.src('./resources/assets/app.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(postcss([autoprefixer({browsers: ['last 30 versions', '> 1%', 'ie 8', 'ie 7']})]))
        .pipe(sourcemaps.write('./maps'))
        .pipe(gulp.dest('./public/css'));
});

/*
 * Watch scss files for changes & recompile
 */
gulp.task('watch', function () {
    gulp.watch('./resources/assets/app.scss', ['sass']);
    gulp.watch(css_files, ['css-main']);
    gulp.watch(css_style, ['css-style']);
    gulp.watch(js_files, ['js-main']);
    gulp.watch(['image-min']);
});

/*
 * Default task, running just `gulp` will compile the sass,
 */
gulp.task('default', ['sass', 'watch']);

gulp.task('css-main', function () {
    return gulp.src(css_files)
        .pipe(sourcemaps.init())
        .pipe(concat('main.css'))
        .pipe(gulp.dest('./public/css'))
        .pipe(rename({suffix: '.min'}))
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest('./public/css'));
});

gulp.task('css-style', function () {
    return gulp.src(css_style)
        .pipe(sourcemaps.init())
        .pipe(rename({suffix: '.min'}))
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest('./public/css'));
});

gulp.task('js-main', function () {
    return gulp.src(js_files)
        .pipe(concat('main.js'))
        .pipe(gulp.dest('./public/js'))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(gulp.dest('./public/js'));
});


gulp.task('image-min', function() {
    return gulp.src('./public/img/*')
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest('./public/images'));
});