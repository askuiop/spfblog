var gulp = require('gulp');

var plugins = require('gulp-load-plugins')();
var del = require("del");
var Q = require("q");



var config = {
    modulesDir : "node_modules/",
    vendorDir : "vendor/",
    assetsDir : "src/BlogBundle/Resources/assets/",
    sassPattern: "sass/**/*.scss",
    cssPattern: "css/**/*.css",
    jsPattern: "js/**/*.js",
    production: !!plugins.util.env.production,
    sourceMap: !plugins.util.env.production,

    revManifestFile: "src/BlogBundle/Resources/assets/rev-manifest.json"
}

var app = {
    addStyle: function (paths, filename) {
        return gulp.src( paths )
            .pipe( plugins.if( config.sourceMap, plugins.sourcemaps.init()) )
            .pipe( plugins.plumber())       //显示错误插件，似乎重复，没用？
            .pipe(plugins.sass())
            .pipe(plugins.concat( 'dist/css/'+filename ))
            .pipe(config.production ? plugins.cleanCss() : plugins.util.noop() )
            .pipe(plugins.rev())
            .pipe( plugins.if( config.sourceMap, plugins.sourcemaps.write(".")) )
            .pipe(gulp.dest('web/'))
            .pipe( plugins.rev.manifest( config.revManifestFile , {
                merge: true
            }) )
            .pipe(gulp.dest('.'))
    },
    addScript: function (paths, filename) {
        gulp.src( paths )
            .pipe( plugins.if( config.sourceMap, plugins.sourcemaps.init()) )
            .pipe( plugins.plumber())
            .pipe(plugins.concat( 'dist/js/' + filename ))
            .pipe(config.production ? plugins.uglify() : plugins.util.noop() )
            .pipe(plugins.rev())
            .pipe( plugins.if( config.sourceMap, plugins.sourcemaps.write(".")) )
            .pipe(gulp.dest('web/'))
            .pipe( plugins.rev.manifest( config.revManifestFile , {
                merge: true
            }) )
            .pipe(gulp.dest('.'))
    },
    addCopy: function (srcFiles, outputDir) {
        return gulp.src( srcFiles )
            .pipe(gulp.dest( outputDir )).on('end', function () {
                console.log('end fonts');
            })
    }
}

gulp.task('styles', function () {
    //app.addStyle([
    //    config.bowerDir + 'bootstrap/dist/css/bootstrap.css',
    //    config.assetsDir + 'sass/main.scss',
    //], 'main.css')
//
    //app.addStyle([
    //    config.assetsDir + 'sass/home.scss',
    //], 'home.css')


    var pipeline = new Pipeline();

    pipeline.add(
        [
            //config.bowerDir + 'bootstrap/dist/css/bootstrap.css',
            config.vendorDir + 'twbs/bootstrap/dist/css/bootstrap.min.css',
            config.assetsDir + config.cssPattern,
            config.assetsDir + config.sassPattern,
            //config.modulesDir + 'nprogress/nprogress.css',
        ], 'main.css'
    )

    //pipeline.add(
    //    [
    //        config.vendorDir + 'twbs/bootstrap/dist/css/bootstrap.min.css',
    //    ], 'bs.css'
    //)
//
    //pipeline.add(
    //    [
    //        config.assetsDir + 'sass/post.scss',
    //    ], 'post.css'
    //)

    return pipeline.run(app.addStyle);

})

gulp.task('scripts',function () {
    app.addScript([
        config.modulesDir + 'jquery/dist/jquery.js',
        config.modulesDir + 'spf/dist/spf.js',
        config.modulesDir + 'nprogress/nprogress.js',
        config.vendorDir + 'twbs/bootstrap/dist/js/bootstrap.min.js',
        config.modulesDir + 'vue/dist/vue.js',
        config.modulesDir + 'axios/dist/axios.js'
    ], 'main.js')
    app.addScript([
        config.assetsDir + config.jsPattern
    ], 'do.js')
})

gulp.task('clean', function () {
    del.sync(config.revManifestFile);
    del.sync("web/dist/*");
})

gulp.task('fonts', function () {
    var pipeline = new Pipeline();
    pipeline.add(
        [ config.vendorDir + 'twbs/bootstrap/dist/fonts/*' ],
        'web/dist/fonts'
    )
    return pipeline.run(app.addCopy);
    //return app.addCopy(
    //    [ config.bowerDir+'font-awesome/fonts/*' ],
    //    'web/dist/fonts'
    //).on('end', function () {
    //    console.log('start fonts');
    //})
})

gulp.task('watch', function () {
    console.log('start watch');
    gulp.watch( config.assetsDir + config.sassPattern, ['styles'])
    gulp.watch( config.assetsDir + config.jsPattern, ['scripts'])
})


gulp.task('blog', plugins.sequence('clean', ['styles', 'scripts', 'fonts']));//顺序执行：

gulp.task('sequence', plugins.sequence('clean', ['styles', 'scripts', 'fonts'], 'watch'));//顺序执行：'clean', run 'styles', 'scripts','fonts' in parallel after 'clean';

/*
gulp.task('styles', function() {
    var pipeline = new Pipeline();

    pipeline.add(
        [
            config.bowerDir + 'bootstrap/dist/css/bootstrap.css',
            config.bowerDir + 'font-awesome/css/font-awesome.css',
            config.assetsDir + 'sass/layout.scss',
            config.assetsDir + 'sass/main.scss'
        ],
        'index.css'
    )

    pipeline.add(
        [
            config.bowerDir + 'bootstrap/dist/css/bootstrap.css',
            config.bowerDir + 'font-awesome/css/font-awesome.css',
            config.assetsDir + 'sass/layout.scss',
            config.assetsDir + 'sass/post.scss'
        ],
        'post.css'
    )

    return pipeline.run(app.addStyle);

});
gulp.task('scripts',['clean'], function () { // 依赖， clean 完成后才执行 (没有返回数据流(Stream)对象)这块有问题
    app.addScript(
        [
            config.bowerDir + 'jquery-1.11.1/dist/jquery.js',
            config.assetsDir+'js/index.js',
        ],
        'index.js'
    )
})

gulp.task('fonts', function () {
    var pipeline = new Pipeline();
    pipeline.add(
        [ config.bowerDir+'font-awesome/fonts/*' ],
        'web/dist/fonts'
    )
    return pipeline.run(app.addCopy);
    //return app.addCopy(
    //    [ config.bowerDir+'font-awesome/fonts/*' ],
    //    'web/dist/fonts'
    //).on('end', function () {
    //    console.log('start fonts');
    //})
})

gulp.task('clean', function () {
    del.sync(config.revManifestFile);
    del.sync("web/dist/*");
})


gulp.task('watch', function () {
    console.log('start watch');
    gulp.watch( config.assetsDir + config.sassPattern, ['styles'])
    gulp.watch( config.assetsDir + config.jsPattern, ['scripts'])
})

gulp.task('default', ['clean', 'styles', 'scripts', 'fonts', 'watch']);

gulp.task('sequence', plugins.sequence('clean', ['styles', 'scripts', 'fonts'], 'watch'));//顺序执行：'clean', run 'styles', 'scripts','fonts' in parallel after 'clean';

*/










var Pipeline = function() {
    this.entries = [];
};
Pipeline.prototype.add = function() {
    this.entries.push(arguments);
};
Pipeline.prototype.run = function(callable) {
    var deferred = Q.defer();
    var i = 0;
    var entries = this.entries;
    var runNextEntry = function() {
        // see if we're all done looping
        if (typeof entries[i] === 'undefined') {
            deferred.resolve();
            return;
        }
        // pass app as this, though we should avoid using "this"
        // in those functions anyways
        callable.apply(app, entries[i]).on('end', function() {
            i++;
            runNextEntry();
        });
    };
    runNextEntry();
    return deferred.promise;
};