jQuery.fn.extend({
    nrTab:function(option){
        /*
            Html Example:
                <aside class="box_news ">
                    <ul>
                        <li class="news_list active"><a href="#tab1"></a></li>
                        <li class="news_list"><a href="#tab2"></a></li>
                        <a class="more-new" href="abc"></a>
                    </ul>
                    <div class="content_news active" id="tab1"></div>
                    <div class="content_news" id="tab2"></div>
                </aside>

            Script Example:
                $('.box_news').nrTab({
                    tab:'.news_list',
                    view:'.content_news',
                    //action:'hover',
                    more:'.more-new',
                    moreCallBack:function(href,activeCode){
                        console.log(1,href, activeCode);
                    }
                });
        */
        var these=jQuery(this);
        var activeClass='active';
        var view=these.find(option.view);
        var tab = these.find(option.tab);
        var tabActive='';
        var navAtc=function(objTab,viewClass){  
            var hrefAttr=option.href || 'href'; 
            var tabActiveObj = $(objTab[0]);       
            objTab.each(function(){
                if($(this).hasClass(activeClass)){                    
                    tabActive=$(this).children('a').attr(hrefAttr) ;
                    tabActiveObj=$(this);
                    if(tabActive==undefined){
                        tabActive = $(this).attr(hrefAttr);
                    }
                }
            });
            if(tabActive==null || tabActive.length<=0 || tabActive==undefined){
                if(option.hashTag==true && window.location.href.match('#.+')){
                    var hasTag = window.location.href.match('#.+')[0];
                    objTab.each(function(){
                        var tabAnchor = $(this).children('a');
                        if(tabAnchor.attr('href')==hasTag){                    
                            tabActive=tabAnchor.attr(hrefAttr) ;
                            tabActiveObj=$(this);
                            if(tabActive==undefined){
                                tabActive = $(this).attr(hrefAttr);
                            }
                            tab.removeClass(activeClass);
                            $(this).addClass(activeClass);
                        }
                    });
                    
                }

                if(tabActive==undefined || tabActive.length<=0){
                    var tabActiveObj = fTab = $(objTab[0]);
                    tabActive=fTab.children('a').attr(hrefAttr);
                    if(tabActive==undefined){
                        tabActive = fTab.attr(hrefAttr);
                    }
                    tab.removeClass(activeClass);
                    fTab.addClass(activeClass);
                }             
            }
            if(tabActive){
                typeof(option.before)=='function'?option.before(these.find(viewClass+tabActive),tabActive,tabActiveObj):null;
                these.find(viewClass).removeClass(activeClass);
                these.find(viewClass+tabActive).addClass(activeClass);
                typeof(option.after)=='function'?option.after(these.find(viewClass+tabActive),tabActive,tabActiveObj):null;
            } 
            return this;           
        }
        
        //action
        option.action=option.action=='hover'?'mouseover':option.action;
        var action=option.action||'click';
        these.on(action,option.tab,function(e){  
            tab.removeClass(activeClass);
            $(this).addClass(activeClass);
            navAtc($(this),option.view);
            if(!option.stopInActive || option.stopInActive==false){
                e.stopPropagation();
                e.preventDefault();
            }
        });
        //view more
        if(option.more){
            var more=these.find(option.more);
            more.on('click',function(e){
                e.stopPropagation();
                e.preventDefault();
                var inActive=tabActive.replace(/[^\w]+/i,'');
                if(option.moreCallBack && typeof(option.moreCallBack)=='function'){
                    option.moreCallBack($(this).attr('href'),inActive);
                } else {
                    window.location.href=$(this).attr('href')+inActive;
                }                
            });
        }
        //call
        
        this.reload = function(){
            if(tab.length<=0){
                tab = these.find(option.tab);
            }
            navAtc(tab,option.view);
        }
        navAtc(tab,option.view)
        return this;
    },
    nrSingleTab:function(opt){
        /*
            Html Example:
                <ul class="content_news">
                    <li class="item active">
                        <a class="nav"></a>
                        <div class="view"></div>
                    </li>
                    <li class="item">
                        <a class="nav"></a>
                        <div class="view"></div>
                    </li>
                </ul>

            Script Example:
                $('.content_news').nrSingleTab({
                    tab:'.nav',
                    action:'hover',
                    inBox:'.item',
                    multiple: true/false
                });
        */
        var these=jQuery(this);
        var activeClass='active';
        var tab=these.find(opt.tab);
        var tabActive='';
        var navAtc=function(objTab){ 
            if(!opt.multiple || opt.multiple==false){
                objTab.closest(these).find(opt.inBox).removeClass(activeClass); 
            }                     
            objTab.parents(opt.inBox).addClass(activeClass); 
        }
        //action
        opt.action=opt.action=='hover'?'mouseover':opt.action;
        var action=opt.action||'mouseover';
        tab.on(action,function(){
            navAtc($(this));
        });
    }
})