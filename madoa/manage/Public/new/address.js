function AddressInit(con){
    var AddressEvent = {
        ID : '',
        isatten : 0,
        //已选中的人
        que : {deptids:[],staffids:[]},
        clear : false,
        callback : function(){
            console.log("请设置回调函数");
        },
        init : function(){
            config = arguments[0];
            if(typeof config != "undefined"){
                this.ID = config.ID;
                if(typeof config.callback != "undefined"){
                    this.callback = config.callback;
                }
                if(typeof config.deptids != "undefined"){
                    this.que.deptids = config.deptids;
                }
                if(typeof config.staffids != "undefined"){
                    this.que.staffids = config.staffids;
                }
                if(typeof config.clear != "undefined"){
                    this.clear = config.clear;
                }
                if(typeof config.clear != "undefined"){
                	this.isatten = config.isatten;
                }
            }
            //初始化已有数据
            this.initque();
            this.toggleDiv();
            this.ocl();
            this.clicka();
            //顶部小叉叉
            //this.sel_delete_click();
            //选择员工 绑定事件
            this.checkboxClick();
            //选择部门绑定事件
            this.deptboxClick();
            //初始化搜索框
            this.staff_search();
            this.submit()
        },
        //搜索
        staff_search : function(){
            var addrUI = this;
            var search_input = $('#'+addrUI.ID+' .token-input-input-token input');
            var searchbox = $("#"+addrUI.ID+" .search-box");
            search_input.on("keyup",function(e){
                var word = $(this).val();
                var word = $.trim(word);
                var staffOneLevel = $('#'+addrUI.ID+" input[name=staff-one-level]").val();
                if(staffOneLevel != '1'){
                	weid = staffOneLevel;
                }else{
                	weid = 1;
                }
                $.getJSON('/admin/addressselect/selectdeptstaff?action=search&word='+word+'&weid='+weid+'&isatten='+addrUI.isatten,function(msg){
                    searchbox.empty();
                    if(msg.deptlist.length>0){
                        for(var i in msg.deptlist){
                            var html = '<li data-id="'+msg.deptlist[i].id+'" data-weid="'+msg.deptlist[i].we_id+'"><img class="mod-tree-people__people-avatar" height="28" width="28" src="" style="display: inline-block;">'+msg.deptlist[i].we_name+'</li>';
                            searchbox.append(html);
                        }
                    }
                    if(msg.stafflist.length>0){
                        for(var i in msg.stafflist){
                            var html = '<li data-id="'+msg.stafflist[i].id+'"><img class="mod-tree-people__people-avatar" height="28" width="28" src="'+msg.stafflist[i].we_avatar+'" style="display: inline-block;">'+msg.stafflist[i].we_name+'</li>';
                            searchbox.append(html);
                        }
                    }
                    searchbox.show(50);
                    //绑定事件
                    addrUI.search_click();
                });
            });
            search_input.on("blur",function(){
                search_input.val("");
                //为了让点击事件生效，350
                searchbox.hide(400);
            });
        },
        //搜索出的部门和员工，绑定点击事件
        search_click : function(){
            var addrUI = this;
            console.log($("#"+addrUI.ID+" .search-box li"));
            $("#"+addrUI.ID+" .search-box li").on("click",function(e){
                var li = $(this);
                var weid = li.attr("data-weid");
                var id = li.attr("data-id");
                if(typeof weid !="undefined" && weid>0){
                    $('#'+addrUI.ID+' .jstree-1 a[data-weid='+weid+']').click();
                }else{
                    var div = $('#'+addrUI.ID+' .listview-list div[data-id='+id+']');
                    if(div.length>0){
                        div.click();
                    }else{
                        //添加员工
                        var json = {};
                        json['id'] = id;
                        json['name'] = li.text();
                        json['we_avatar'] = li.find("img").attr("src");;
                        if(addrUI.que_search(json)){
                            //删除队列
                            addrUI.que_delete(json);
                            //取消显示
                            addrUI.view_delete(json);
                        }else{
                            addrUI.pushque(json);
                        }
                    }
                }
            });
        },
        //部门员工切换
        toggleDiv : function(){
            var addrUI = this;
            $('#'+addrUI.ID+" .toggle-div li").each(function(i){
                $(this).on("click",function(){
                    //标签切换
                    $('#'+addrUI.ID+" .toggle-div li a").removeClass("active");
                    $(this).find("a").addClass("active");
                    //内容切换
                    var div = $('#'+addrUI.ID+" .s-div-toggle");
                    div.hide(200);
                    div.eq(i).show(50);
                });
            });
        },
        //小图标 部门展与 收拢
        ocl : function(){
            var addrUI = this;
            $('#'+addrUI.ID+" .jstree .jstree-ocl").on("click",function(e){
                var li = $(this).parent();
                var ul = $(this).siblings("ul");
                li.toggleClass("jstree-open");
                if(li.hasClass("jstree-open")){
                    ul.show(200);
                }else{
                    ul.hide(200);
                }
                e.stopPropagation();
            });
        },
        //点击行 ，部门展开
        clicka : function(){
            var addrUI = this;
            $('#'+addrUI.ID+" .jstree-2 a").on("click",function(e){
                //微信部门id
                var weid = $(this).attr("data-weid");
                $(this).parent().addClass("jstree-open");
                $(this).siblings("ul").show(200);
                addrUI.ajaxStaff(weid);
                e.stopPropagation();
            });
        },
        //顶部部门和员工小叉叉的关闭功能
        sel_delete_click : function(){
            var addrUI = this;
            var li = arguments[0];
            if(typeof li != "undefined"){
                li.find(".token-input-delete-token").on("click",function(){
                    var dept_id = $(this).parent().attr("dept-id");
                    var staff_id = $(this).parent().attr("staff-id");
                    //部门
                    if(typeof dept_id != "undefined" && dept_id>0){
                        $('#'+addrUI.ID+" .jstree-1 a[data-id="+dept_id+"]").click();
                    }else if(typeof staff_id != "undefined" && staff_id>0){
                        var staff = $('#'+addrUI.ID+" .js_mbodyr div[data-id="+staff_id+"]");
                        console.log(staff.length);
                        if(staff.length>0){
                            staff.click();
                        }else{
                            var json = {};
                            json['id'] = li.attr("staff-id");
                            json['name'] = li.find("span").eq(1).text();
                            json['we_avatar'] = li.find(".mod-lozenges__icon-org").attr("avatar-src");;
                            console.log(json);
                            if(addrUI.que_search(json)){
                                //删除队列
                                addrUI.que_delete(json);
                                //取消显示
                                addrUI.view_delete(json);
                            }else{
                                addrUI.pushque(json);
                            }
                        }
                    }
                });
                return li;
            }else{
                var list = $('#'+addrUI.ID+" .token-input-list");
                list.find(".token-input-delete-token").on("click",function(){
                    var dept_id = $(this).parent().attr("dept-id");
                    var staff_id = $(this).parent().attr("staff-id");
                    //部门
                    if(typeof dept_id != "undefined" && dept_id>0){
                        $('#'+addrUI.ID+" .jstree-1 a[data-id="+dept_id+"]").click();
                    }else if(typeof staff_id != "undefined" && staff_id>0){
                        $('#'+addrUI.ID+" .js_mbodyr div[data-id="+staff_id+"]").click();
                    }
                });
            }
        },
        //根据部门id获取员工
        ajaxStaff : function(){
            var addrUI = this;
            var weid = arguments[0] ? arguments[0] : 1;
            var page = arguments[1] ? arguments[1] : 1;
            var staffOneLevel = $('#'+addrUI.ID+" input[name=staff-one-level]").val();
            if(weid == '1' && staffOneLevel != '1'){
            	weid = staffOneLevel;
            }
            $.getJSON('/admin/addressselect/selectdeptstaff?deptid='+weid+'&page='+page+'&isatten='+addrUI.isatten,function(data){
                //员工容器
                var container = $('#'+addrUI.ID+" .listview-list");
                container.empty();
                var msg = data.list;
                console.log(data);
                if(msg.length>0){
                    var sarr = new Array();
                    for (var i in addrUI.que.staffids){
                        sarr.push(addrUI.que.staffids[i].id);
                    }
                    for(var i in msg){
                        var html = '';
                        html += '<div class="mod-tree-people__people-item  listview-item" data-id="'+msg[i].id+'">';
                        html += '<img class="mod-tree-people__people-avatar" height="28" width="28" src="'+(msg[i].we_avatar.length>0 ? msg[i].we_avatar : avatar)+'" style="display: inline-block;">';
                        html += '<span class="mod-tree-people__people-name">'+msg[i].we_name+'</span>';
                        if(sarr.indexOf(msg[i].id)==-1){
                            html += '<div class="s-checker"></div>';
                        }else{
                            html += '<div class="s-checker2"></div>';
                        }
                        html += '</div>';
                        container.append(html);
                    }
                    if(data.ispageshow){
                        var pagestyle = '<div class="s-pages" data-weid="'+weid+'"><span>当前页：</span><span>'+data.page+'</span>&nbsp;&nbsp;';
                        if(data.page!=1){
                            pagestyle += '<a href="javascript:;" class="s-pre">上一页</a>';
                        }
                        if(data.page != data.pagecount){
                            pagestyle += '<a href="javascript:;"class="s-next">下一页</a>';
                        }
                        pagestyle += '</div>';
                        container.append(pagestyle);
                    }
                    //员工绑定事件
                    addrUI.checkboxClick();
                }else{
                    container.html("此部门下没有员工");
                }
            });
        },
        //员工/分页 绑定事件函数
        checkboxClick : function(){
            var addrUI = this;
    
            //员工
            $('#'+addrUI.ID+" .listview-item").unbind("click").bind("click",function(){
                var item = $(this);
                var checkbox = item.find(".s-checker,.s-checker2");
                if(checkbox.hasClass("s-checker")){
                    checkbox.removeClass("s-checker");
                    checkbox.addClass("s-checker2");
                }else if(checkbox.hasClass("s-checker2")){
                    checkbox.removeClass("s-checker2");
                    checkbox.addClass("s-checker");
                }
                var json = {};
                json['id'] = item.attr("data-id");
                json['name'] = item.find("span").text();
                json['we_avatar'] = item.find("img").attr("src");;
                if(addrUI.que_search(json)){
                    //删除队列
                    addrUI.que_delete(json);
                    //取消显示
                    addrUI.view_delete(json);
                }else{
                    addrUI.pushque(json);
                }
            });
            //分页
            $('#'+addrUI.ID+" .s-pages a").unbind("click").bind("click",function(){
                var a = $(this);
                var weid = a.parent().attr("data-weid");
                var page = a.parent().find("span").eq(1).html();
                //上一页
                if(a.hasClass("s-pre")){
                    addrUI.ajaxStaff(weid,parseInt(page)-1);
                //下一页
                }else if(a.hasClass("s-next")){
                    addrUI.ajaxStaff(weid,parseInt(page)+1);
                }
            });
        },
        //部门绑定事件 两个点击事件 展开和选中
        deptboxClick : function(){
            var addrUI = this;
            $('#'+addrUI.ID+" .jstree-1 a").on("click",function(e){
                var a = $(this);
                //切换
                a.parent().addClass("jstree-open");
                a.siblings("ul").show(200);
                //选中
                var checkbox = a.find(".jstree-checkbox,.jstree-checkbox2");
                if(checkbox.hasClass("jstree-checkbox")){
                    checkbox.removeClass("jstree-checkbox");
                    checkbox.addClass("jstree-checkbox2");
                }else if(checkbox.hasClass("jstree-checkbox2")){
                    checkbox.removeClass("jstree-checkbox2");
                    checkbox.addClass("jstree-checkbox");
                }
                var json = {};
                json['id'] = a.attr("data-id");
                json['we_id'] = a.attr("data-weid");
                json['name'] = a.text();
                if(addrUI.que_search(json)){
                    //删除队列
                    addrUI.que_delete(json);
                    //取消显示
                    addrUI.view_delete(json);
                }else{
                    addrUI.pushque(json);
                }
                e.stopPropagation();
            });
        },
        //在顶部显示选中的部门与员工
        initque : function(){
            var addrUI =this;
            //清空tester 并初始化传入的数据
            this.view_callback();
            //初始化时重置一些数据
            if(addrUI.clear){
                //清空顶部已选中的部门和员工
                $('#'+addrUI.ID+" .token-input-list .token-input-token").remove();
                //清除勾选的部门
                $('#'+addrUI.ID+" .jstree-1 a").find(".jstree-checkbox2").each(function(){
                    var a = $(this).parent();
                    var json = {};
                    json['id'] = a.attr("data-id");
                    json['we_id'] = a.attr("data-weid");
                    json['name'] = a.text();
                    if(addrUI.que_search(json)){
                       
                    }else{
                        $(this).removeClass("jstree-checkbox2").addClass("jstree-checkbox");
                    }
                });
                //清除勾选的员工
                $('#'+addrUI.ID+" .listview-list").find(".s-checker2").each(function(){
                    var json = {};
                    var item = $(this).parent();
                    json['id'] = item.attr("data-id");
                    json['name'] = item.find("span").text();
                    json['we_avatar'] = item.find("img").attr("src");;
                    if(addrUI.que_search(json)){
                        
                    }else{
                        $(this).removeClass("s-checker2").addClass("s-checker");
                    }
                });
            }
            
            if(this.que.deptids.length>0){
                for (var i in this.que.deptids){
                    this.viewaddr(this.que.deptids[i]);
                    //this.viewaddr_callback(this.que.deptids[i]);
                }
            }
            if(this.que.staffids.length>0){
                for (var i in this.que.staffids){
                    this.viewaddr(this.que.staffids[i]);
                    //this.viewaddr_callback(this.que.staffids[i]);
                }
            }
        },
        //添加员工或者部门
        pushque : function(data){
            
            //添加部门
            if(data['we_id']){
                this.viewaddr(data);
                this.que.deptids.push(data);
            //添加员工
            }else{
                this.viewaddr(data);
                this.que.staffids.push(data);
            }
            
        },
        //显示部门 员工 html
        viewaddr : function(data){
            var addrUI = this;
            var list = $('#'+addrUI.ID+" .token-input-list");
            var list_search = list.find(".token-input-input-token");
            if(data['we_id']){
                var html = '';
                html += '<li class="mod-lozenges__item token-input-token" dept-weid="'+data['we_id']+'" dept-id="'+data['id']+'">';
                html += '<span class="mod-lozenges__icon">';
                html += '<i class="mod-lozenges__icon-org"></i>';
                html += '</span>';
                html += '<span class="mod-lozenges__text">'+data['name']+'</span>';
                html += '<span class="token-input-delete-token">';
                html += '<i class="mod-lozenges__close-icon"></i>';
                html += '</span>';
                html += '</li>';
                list_search.before(html);
                //添加事件
                addrUI.sel_delete_click(list_search.prev());
            }else if(typeof data['we_avatar'] != "undefined"){
                var html = '';
                html += '<li class="mod-lozenges__item token-input-token" staff-id="'+data['id']+'">';
                html += '<span class="mod-lozenges__icon">';
                html += '<i class="mod-lozenges__icon-org" avatar-src="'+(data['we_avatar'] ? data['we_avatar'] : avatar)+'" style="background: url('+(data['we_avatar'] ? data['we_avatar'] : avatar)+'); background-size:100%;"></i>';
                html += '</span>';
                html += '<span class="mod-lozenges__text">'+data['name']+'</span>';
                html += '<span class="token-input-delete-token">';
                html += '<i class="mod-lozenges__close-icon"></i>';
                html += '</span>';
                html += '</li>';
                list_search.before(html);
                //添加事件
                addrUI.sel_delete_click(list_search.prev());
            }
        },
        //显示部门 员工 html
        viewaddr_callback : function(data){
            var addrUI = this;
            var list = $('div[view-for='+addrUI.ID+'] .token-input-list');
            var list_search = list.find(".token-input-input-token");
            if(data['we_id']){
                var html = '';
                html += '<li class="mod-lozenges__item token-input-token" dept-weid="'+data['we_id']+'" dept-id="'+data['id']+'">';
                html += '<span class="mod-lozenges__icon">';
                html += '<i class="mod-lozenges__icon-org"></i>';
                html += '</span>';
                html += '<span class="mod-lozenges__text">'+data['name']+'</span>';
                html += '<span class="token-input-delete-token">';
                //html += '<i class="mod-lozenges__close-icon"></i>';
                html += '</span>';
                html += '</li>';
                list_search.before(html);
                //添加事件
                addrUI.sel_delete_click(list_search.prev());
            }else if(typeof data['we_avatar'] != "undefined"){
                var html = '';
                html += '<li class="mod-lozenges__item token-input-token" staff-id="'+data['id']+'">';
                html += '<span class="mod-lozenges__icon">';
                html += '<i class="mod-lozenges__icon-org" style="background: url('+(data['we_avatar'] ? data['we_avatar'] : avatar)+');background-size:100%;"></i>';
                html += '</span>';
                html += '<span class="mod-lozenges__text">'+data['name']+'</span>';
                html += '<span class="token-input-delete-token">';
                //html += '<i class="mod-lozenges__close-icon"></i>';
                html += '</span>';
                html += '</li>';
                list_search.before(html);
                //添加事件
                addrUI.sel_delete_click(list_search.prev());
            }
        },
        //查找是否在已选列表里
        que_search : function(data){
            if(data['we_id']){
                for(var i in this.que.deptids){
                    if(this.que.deptids[i].id==data.id) return true;
                }
            }else{
                for (var i in this.que.staffids){
                    if(this.que.staffids[i].id==data.id) return true;
                }
            }
        },
        //查找是否在已选列表里
        que_delete : function(data){
            if(data['we_id']){
                for(var i in this.que.deptids){
                    if(this.que.deptids[i].id==data.id) this.que.deptids.splice(i,1);
                }
            }else if(data['we_avatar']){
                for (var i in this.que.staffids){
                    if(this.que.staffids[i].id==data.id) this.que.staffids.splice(i,1);
                }
            }
        },
        //取消显示
        view_delete : function(data){
            var addrUI = this;
            if(data['we_id']){
                $('#'+addrUI.ID+" li[dept-id="+data['id']+"]").remove();
            }else if(data['we_avatar']){
                $('#'+addrUI.ID+" li[staff-id="+data['id']+"]").remove();
            }
        },
        view_callback : function(){
            var addrUI = this;
            var list = $('div[view-for='+addrUI.ID+'] .token-input-list');
            var list_search = list.find(".token-input-token").remove();
            if(this.que.deptids.length>0){
                for (var i in this.que.deptids){
                    this.viewaddr_callback(this.que.deptids[i]);
                }
            }
            if(this.que.staffids.length>0){
                for (var i in this.que.staffids){
                    this.viewaddr_callback(this.que.staffids[i]);
                }
            }
        },
        //确定选中
        submit : function(){
            var addrUI = this;
            $('#'+addrUI.ID+" .addr-submit").on("click",function(e){
                addrUI.callback(addrUI.que);
                addrUI.view_callback();
                console.log(addrUI.que);
            });
        },
    }
return AddressEvent.init(con);
}