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

var vendor_files = [
    './resources/assets/sass/vendor/bootstrap.min.css',
    './resources/assets/sass/vendor/flag-icon.css',
    './resources/assets/sass/vendor/jquery.datetimepicker.css',
    './resources/assets/sass/vendor/jquery.jscrollpane.css',
    './resources/assets/sass/vendor/jquery-ui-1.10.4.tooltip.css',
    './resources/assets/sass/vendor/jquery.jsonview.css',
    './resources/assets/sass/vendor/select2.min.css',
    './resources/assets/sass/vendor/leaflet.css'
];

var css_style = [
    './public/css/style.css'
];

var app_style = [
    './public/css/app.css'
];

var lite_style = [
    './public/lite/css/lite.css'
];

var tz_style = [
    './public/tz/css/tz.css'
];
var np_style = [
    './public/np/css/np.css'
];

var js_files = [
    './public/js/jquery.js',
    './public/js/bootstrap.min.js',
    './public/js/modernizr.js',
    './public/js/jquery-ui-1.10.4.tooltip.js',
    './public/js/jquery.cookie.js',
    './public/js/jquery.mousewheel.js',
    './public/js/jquery.jscrollpane.min.js',
    './public/js/select2.min.js',
    './public/js/jquery.datetimepicker.full.min.js',
    './public/js/script.js',
    './public/js/datatable.js'
];


/**
 * Compile files from _scss
 */

gulp.task('style-sass', function () {
    return gulp.src('./resources/assets/sass/style.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(postcss([autoprefixer({browsers: ['last 30 versions', '> 1%', 'ie 8', 'ie 7']})]))
        .pipe(sourcemaps.write('./maps'))
        .pipe(gulp.dest('./public/css'));
});

gulp.task('sass', function () {
    return gulp.src('./resources/assets/sass/app.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(postcss([autoprefixer({browsers: ['last 30 versions', '> 1%', 'ie 8', 'ie 7']})]))
        .pipe(sourcemaps.write('./maps'))
        .pipe(gulp.dest('./public/css'));
});

gulp.task('lite-sass', function () {
    return gulp.src('./resources/assets/sass/lite/lite.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(postcss([autoprefixer({browsers: ['last 30 versions', '> 1%', 'ie 8', 'ie 7']})]))
        .pipe(sourcemaps.write('./maps'))
        .pipe(gulp.dest('./public/lite/css'));
});

gulp.task('tz-sass', function () {
    var tz_style = gulp.src('./resources/assets/sass/tz/tz.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(postcss([autoprefixer({browsers: ['last 30 versions', '> 1%', 'ie 8', 'ie 7']})]))
        .pipe(sourcemaps.write('./maps'))
        .pipe(gulp.dest('./public/tz/css'));
});
gulp.task('np-sass', function () {
    var np_style = gulp.src('./resources/assets/sass/np/np.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(postcss([autoprefixer({browsers: ['last 30 versions', '> 1%', 'ie 8', 'ie 7']})]))
        .pipe(sourcemaps.write('./maps'))
        .pipe(gulp.dest('./public/np/css'));
});

/*
 * Watch scss files for changes & recompile
 */
gulp.task('watch', function () {
    gulp.watch('./resources/assets/sass/style.scss', ['style-sass']);
    gulp.watch('./resources/assets/sass/app.scss', ['sass']);
    gulp.watch('./resources/assets/sass/lite/lite.scss', ['lite-sass']);
    gulp.watch('./resources/assets/sass/tz/**/*.scss',['tz-sass']);
    gulp.watch('./resources/assets/sass/np/**/*.scss',['np-sass']);
    gulp.watch(vendor_files, ['vendor-main']);
    gulp.watch(css_style, ['style-main']);
    gulp.watch(app_style, ['app-main']);
    gulp.watch(lite_style, ['lite-main']);
    gulp.watch(tz_style, ['tz-main']);
    gulp.watch(np_style, ['np-main']);
    gulp.watch(js_files, ['js-main']);
    gulp.watch(['image-min']);
});

gulp.task('vendor-main', function () {
    return gulp.src(vendor_files)
        .pipe(sourcemaps.init())
        .pipe(concat('vendor.css'))
        .pipe(gulp.dest('./public/css'))
        .pipe(rename({suffix: '.min'}))
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest('./public/css'));
});

gulp.task('style-main', function () {
    return gulp.src(css_style)
        .pipe(sourcemaps.init())
        .pipe(rename({suffix: '.min'}))
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest('./public/css'));
});

gulp.task('app-main', function () {
    return gulp.src(app_style)
        .pipe(sourcemaps.init())
        .pipe(concat('main.css'))
        .pipe(gulp.dest('./public/css'))
        .pipe(rename({suffix: '.min'}))
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest('./public/css'));
});

gulp.task('lite-main', function () {
    return gulp.src(lite_style)
        .pipe(sourcemaps.init())
        .pipe(rename({suffix: '.min'}))
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest('./public/lite/css'));
});

gulp.task('tz-main', function () {
    return gulp.src(tz_style)
        .pipe(sourcemaps.init())
        .pipe(rename({suffix: '.min'}))
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest('./public/tz/css'));
});
gulp.task('np-main', function () {
    return gulp.src(np_style)
        .pipe(sourcemaps.init())
        .pipe(rename({suffix: '.min'}))
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest('./public/np/css'));
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

/*
 * Default task, running just `gulp` will compile the sass,
 */
gulp.task('default', ['style-sass',"sass","lite-sass","tz-sass","np-sass", 'watch']);






