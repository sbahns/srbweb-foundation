var gulp  = require('gulp'),
    sass = require('gulp-sass'),
    cssnano = require('gulp-cssnano'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    gulpif = require('gulp-if');

var env,
	cssStyle;

env = process.env.NODE_ENV || 'development';

if ( env === 'development' ) {
	cssStyle = 'expanded';
} else {
	cssStyle = 'compressed';
}

var sassPaths = [
	'bower_components/foundation-sites/scss',
	'bower_components/motion-ui/src'
];

gulp.task('sass', function() {
	return gulp.src('../srbweb-f6-child/scss/app.scss')
		.pipe(gulpif(env === 'development', sourcemaps.init()))
		.pipe(sass({ includePaths: sassPaths }).on('error', sass.logError))
		.pipe(sass({ sourceComments: 'map', sourceMap: 'sass', outputStyle: cssStyle }))
		.pipe(autoprefixer({ browsers: ['last 2 versions', 'ie >= 9'] }))
		.pipe(gulpif(env === 'development', cssnano()))
		.pipe(gulpif(env === 'development', sourcemaps.write()))
		.pipe(gulp.dest('../srbweb-f6-child/css'));
});


gulp.task('watch', function() {
	gulp.watch('../srbweb-f6-child/scss/*.scss', ['sass']);
});

gulp.task('default', ['sass'], function() {
	gulp.watch(['scss/**/*.scss'], ['sass']);
});
