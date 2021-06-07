const gulp = require('gulp');
const responsive = require('gulp-responsive');
const imagemin = require('gulp-imagemin');
const webp = require('gulp-webp');
const pngquant = require('imagemin-pngquant');
const mozjpeg = require('imagemin-mozjpeg');
const critical = require('critical');

function resizeImages() {
    return gulp
        .src('public/compress_img/original/**/*.{png,jpg,jpeg}')
        .pipe(
            responsive({
                '**/*.{png,jpg}': [{
                        width: 100,
                        rename: {
                            suffix: '-100px'
                        }
                    },
                    {
                        width: 300,
                        rename: {
                            suffix: '-300px'
                        }
                    }, {
                        width: 600,
                        rename: {
                            suffix: '-600px'
                        }
                    }, {
                        width: 1000,
                        rename: {
                            suffix: '-1000px'
                        }
                    }, {
                        width: 1400,
                        rename: {
                            suffix: '-1400px'
                        }
                    }, {
                        width: 1900,
                        rename: {
                            suffix: '-1900px'
                        }

                    }
                ]
            }, {
                quality: 70,
                errorOnEnlargement: false,
                withMetadata: false,
                skipOnEnlargement: false,
                errorOnUnusedConfig: false,
                errorOnUnusedImage: false
            })
        )
        .pipe(gulp.dest('public/compress_img/rescaled-img'));
}

function imgMin() {
    return gulp.src("public/compress_img/rescaled-img/**/*.{png,jpg,jpeg}")
        .pipe(imagemin([
            pngquant({
                quality: [0.7, 0.9], // When used more then 70 the image wasn't saved
                speed: 1, // The lowest speed of optimization with the highest quality
                dithering: 1 // Controls level of dithering (0 = none, 1 = full).
            }),
            mozjpeg({ quality: 65 })
        ]))
        .pipe(gulp.dest("public/compress_img/rescaled-img"));
}

function covertImgsToWebp() {
    return gulp.src('public/compress_img/original/**/*.{png,jpg,jpeg}')
        .pipe(webp())
        .pipe(gulp.dest('public/compress_img/webp-img'));
}


async function criticalCss() {
    const config = {
        paths: {
            base: 'http://peachy.cm/',
            templates: './public/css/',
            suffix: '_critical.min'
        },
        urls: [
            { url: '', template: 'home' },
            { url: 'trans', template: 'trans' }
        ],
        criticalOptions: {
            minify: true
        }
    };

    config.urls.forEach((template) => {
        const criticalSrc = config.paths.base + template.url;
        const criticalDest = config.paths.templates + template.template + config.paths.suffix + '.css';
        critical.generate(Object.assign({
            src: criticalSrc,
            dest: criticalDest,
        }, config.criticalOptions));
    });
}

// exports.default = gulp.series(resizeImages, imgMin, covertImgsToWebp);
exports.minifyImgs = gulp.series(resizeImages, imgMin, covertImgsToWebp);

exports.criticalCss = gulp.series(criticalCss);