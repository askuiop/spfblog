$(function () {
    spf.init();
    //NProgress.configure({ showSpinner: false });

    $(document).on("spfclick", function(event) {
      // Show progress bar
      console.log(event);
      console.log(event.currentTarget);
      NProgress.start();
    });

    $(document).on('spfrequest', function (event) {
        NProgress.set(0.6);
    });

    $(document).on('spfprocess', function (event) {

        NProgress.inc();
        //NProgress.set(0.6);
        //NProgress.set(0.8);

    });


    $(document).on('spfdone', function (event) {

        NProgress.set(1.0);
        NProgress.done();
        // Finish request and remove progress bar
        //NProgress.remove();

    });

    $(document).on('spfhistory', function (event) {
        

        NProgress.set(0.7);
        NProgress.set(0.9);
        NProgress.set(1.0);
        NProgress.done();
        

    });

    
});
