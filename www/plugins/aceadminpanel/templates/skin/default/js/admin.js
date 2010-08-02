function is_array( mixed_var ) {
    return ( mixed_var instanceof Array );
}

function is_object( mixed_var ){
    if(is_array( mixed_var )) {
        return false;
    } else {
        return (mixed_var !== null) && (typeof( mixed_var ) == 'object');
    }
}

var js_admin = {
    "info_phpinfo": {
        action: function(n, close) {
            var el=$('section_'+n);
            var cl=$('close_'+n);
            if (el) {
                if (typeof(close)=="undefined") {
                    close = !sections[n];
                }
                if (!close) {
                    el.style.display='';
                    sections[''+n]=0;
                    if (cl) cl.set('html', '[&nbsp;-&nbsp;]');
                    this.save();
                } else {
                    el.style.display='none';
                    sections[''+n]=1;
                    if (cl) cl.set('html', '[&nbsp;+&nbsp;]');
                    this.save();
                }
            }
        },
        save: function() {
            var s = JSON.encode(sections);
            Cookie.write('adm_phpinfo_sec', s, {
                duration: 365
            });
        },
        load: function() {
            var s = Cookie.read('adm_phpinfo_sec');
            if (s) {
                var a = JSON.decode(s);
            }
            if (is_object(a)) {
                return a;
            }
            else {
                return {};

        }
    }
},
"ajax_url": function(url, query) {
    var request_url = DIR_WEB_ROOT+url;
    if (query)
        request_url += '?'+query+'&security_ls_key='+LIVESTREET_SECURITY_KEY;
    else
        request_url += '?security_ls_key='+LIVESTREET_SECURITY_KEY;
    return request_url;
}
}

function AdminBlogDelete(msg, name, blog_id) {
    if (name) msg = msg.replace('%%blog%%', name);
    if (confirm(msg)) {
        //var url = DIR_WEB_ROOT+'/admin/blogs/delete/?blog_id='+blog_id+'&security_ls_key='+LIVESTREET_SECURITY_KEY;
        var url = js_admin.ajax_url('/admin/blogs/delete/', 'blog_id='+blog_id);
        document.location.href = url;
        return true;
    }
    return false;
}

function AdminTopicDelete(msg, name, topic_id) {
    if (name) msg = msg.replace('%%topic%%', name);
    if (confirm(msg)) {
        //var url = DIR_WEB_ROOT+'/admin/topics/delete/?topic_id='+topic_id+'&security_ls_key='+LIVESTREET_SECURITY_KEY;
        var url = js_admin.ajax_url('/admin/topics/delete/', 'topic_id='+topic_id);
        document.location.href = url;
        return true;
    }
    return false;
}

function AdminGetElements(className) {
    return $(document.body).getElements(className);
}

function AdminSetStyleProp(elements, property, value) {
    elements.each(function(item, index){
        item.setStyle(property, value);
    });
}