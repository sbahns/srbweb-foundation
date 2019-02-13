var gulp  = require('gulp'),
    sass = require('gulp-sass'),
    cssnano = require('gulp-cssnano'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    gulpif = require('gulp-if');
    runSequence = require('run-sequence');

var env,
	cssStyle;

// env = process.env.MEQ_WP_SERVER;
//
// if ( env === 'dev' ) {
// 	cssStyle = 'expanded';
// } else {
// 	cssStyle = 'compressed';
// }

var sassPaths = [
	'bower_components/foundation-sites/scss',
	'bower_components/motion-ui/src'
];

//Uncomment out this section if you need to also compile the parent theme scss
gulp.task('sass_parent', function() {
    return gulp.src('../srbweb-f6/scss/app.scss')
  		.pipe(sass({ includePaths: sassPaths }).on('error', sass.logError))
  		.pipe(sass({ outputStyle: 'expanded' }))//change this to compressed on production
  		.pipe(autoprefixer({ browsers: ['last 2 versions', 'ie >= 9'] }))
  		.pipe(gulp.dest('css'));
});

gulp.task('sass_dev', function() {
	return gulp.src('../srbweb-f6-child/scss/app.scss')
		.pipe(sourcemaps.init())
		.pipe(sass({ includePaths: sassPaths }).on('error', sass.logError))
		.pipe(sass({ sourceComments: 'map', sourceMap: 'sass', outputStyle: 'expanded' }))
		.pipe(autoprefixer({ browsers: ['last 2 versions', 'ie >= 9'] }))
		.pipe(cssnano())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('../srbweb-f6-child/css/dev'));
});

gulp.task('sass_prod', function() {
    return gulp.src('../srbweb-f6-child/scss/app.scss')
  	//	.pipe(sourcemaps.init())
  		.pipe(sass({ includePaths: sassPaths }).on('error', sass.logError))
  		.pipe(sass({ outputStyle: 'compressed' }))
  		.pipe(autoprefixer({ browsers: ['last 2 versions', 'ie >= 9'] }))
  	//	.pipe(cssnano())
  	//	.pipe(sourcemaps.write())
  		.pipe(gulp.dest('../srbweb-f6-child/css'));
});

// Uncomment out this section (and comment out the following section) if you need to also compile the parent theme scss
gulp.task('sass', function(callback) {
  runSequence('sass_parent','sass_dev', 'sass_prod', callback);
});

 // gulp.task('sass', function(callback) {
 //   runSequence('sass_dev', 'sass_prod', callback);
 // });

gulp.task('watch', function() {
	gulp.watch('../srbweb-f6-child/scss/*.scss', ['sass']);
	//gulp.watch('../srbweb-f6-child/tribe-events/*.scss', ['sass']);
});

gulp.task('default', ['sass'], function() {
	gulp.watch(['scss/**/*.scss'], ['sass']);
});
