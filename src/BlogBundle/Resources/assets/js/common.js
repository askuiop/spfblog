

var cpnt = Vue.component('pc-search-result', {
    delimiters: ['${', '}'],
    template: '#tpl-pc-search-result',
    data: function () {
        return {
            tmp: [],
        };
    },
    compute: {
        listData: {
            set:function (newValue) {
                ;
            },
            get:function () {
                this.tmp = this.listData.concat();
                return this.tmp;
            }
        }
    },
    props: [ 'listData' ],
    mounted: function () {
        self = this;
        $('body').click(function () {
            self.listData = [];
        });
    }

});


var pcSearcher = new Vue({
    delimiters: ['${', '}'],
    el: "#pc-seacher" ,
    methods: {
        alt: function () {
            console.log(this.keywords);

        }
    },
    data: {
        keywords: '',
        listData: [],
        timer: null,
    },
    watch: {
        keywords: function (newVale) {
            clearInterval(this.timer);
            var self = this;
            if (newVale.length>2) {
                this.timer = setTimeout(function () {
                    axios.get(app.baseUrl+'/search?kw='+newVale)
                        .then(function (response) {

                            self.listData = response.data;
                            console.log(response.data );
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                },400)


            }
        }
    }
});




$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $(".mh-min-search").click(function (event) {
        $('.cpnt_search').show().find('input').focus();


    });
    $('.btn-close').click(function () {
        $(this).parent().parent().hide();
    });
    $('.cover').click(function () {
        $(this).parent().hide();
    });


    $(".mh-title button").click(function (event) {
        if ($(window).width()<768) {
            baseCover.show(function () {
                $(".root-cover").click(function (event) {
                    $(".mh-title-plus i").trigger('click');
                });
            });
            $('#guide-container').toggleClass('wap-guide-show');
            $('#guide-container .mh-title-plus').toggleClass('wap-guide-mh-show');



        }else{

            $('#guide-container').toggleClass('hide-left');
            $("#container").toggleClass('expanded');
        }


    });
    $(".mh-title-plus i").click(function () {

        $('#guide-container').removeClass('wap-guide-show');
        $('#guide-container .mh-title-plus').removeClass('wap-guide-mh-show');
        baseCover.hide(function(){
            $(".root-cover").unbind('click');
        });
    });


    jQuery('.scrollbar-macosx').scrollbar();



})


var baseCover= {
    el: $('.root-cover'),
    show: function(cb){
        this.el.show();
        $('html').addClass('noscroll');
        this.el.on('touchmove', function(event) {
            event.preventDefault();
        });

        if (typeof cb != undefined) {
            cb();
        }

    },
    hide: function(cb){
        $('.root-cover').hide();
        $('html').removeClass('noscroll');
        if (typeof cb != undefined) {
            cb();
        }

    }
}
