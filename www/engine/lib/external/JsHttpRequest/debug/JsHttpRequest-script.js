/**
 * JsHttpRequest: JavaScript "AJAX" data loader (script support only!)
 *
 * @license LGPL
 * @author Dmitry Koterov, http://en.dklab.ru/lib/JsHttpRequest/
 * @version 5.x $Id$
 */
// {{{
function JsHttpRequest() {
    // Standard properties.
    var t = this;
    t.onreadystatechange = null;
    t.readyState         = 0;
    t.responseText       = null;
    t.responseXML        = null;
    t.status             = 200;
    t.statusText         = "OK";
    // JavaScript response array/hash
    t.responseJS         = null;

    // Additional properties.
    t.caching            = false;        // need to use caching?
    t.loader             = null;         // loader to use ('form', 'script', 'xml'; null - autodetect)
    t.session_name       = "PHPSESSID";  // set to SID cookie or GET parameter name

    // Internals.
    t._ldObj              = null;  // used loader object
    t._reqHeaders        = [];    // collected request headers
    t._openArgs          = null;  // parameters from open()
    t._errors = {
        inv_form_el:        'Invalid FORM element detected: name=%, tag=%',
        must_be_single_el:  'If used, <form> must be a single HTML element in the list.',
        js_invalid:         'JavaScript code generated by backend is invalid!\n%',
        url_too_long:       'Cannot use so long query with GET request (URL is larger than % bytes)',
        unk_loader:         'Unknown loader: %',
        no_loaders:         'No loaders registered at all, please check JsHttpRequest.LOADERS array',
        no_loader_matched:  'Cannot find a loader which may process the request. Notices are:\n%'
    }
    
    /**
     * Aborts the request. Behaviour of this function for onreadystatechange() 
     * is identical to IE (most universal and common case). E.g., readyState -> 4
     * on abort() after send().
     */
    t.abort = function() { with (this) {
        if (_ldObj && _ldObj.abort) _ldObj.abort();
        _cleanup();
        if (readyState == 0) {
            // start->abort: no change of readyState (IE behaviour)
            return;
        }
        if (readyState == 1 && !_ldObj) {
            // open->abort: no onreadystatechange call, but change readyState to 0 (IE).
            // send->abort: change state to 4 (_ldObj is not null when send() is called)
            readyState = 0;
            return;
        }
        _changeReadyState(4, true); // 4 in IE & FF on abort() call; Opera does not change to 4.
    }}
    
    /**
     * Prepares the object for data loading.
     * You may also pass URLs like "GET url" or "script.GET url".
     */
    t.open = function(method, url, asyncFlag, username, password) { with (this) {
        // Extract methor and loader from the URL (if present).
        if (url.match(/^((\w+)\.)?(GET|POST)\s+(.*)/i)) {
            this.loader = RegExp.$2? RegExp.$2 : null;
            method = RegExp.$3;
            url = RegExp.$4; 
        }
        // Append SID to original URL. Use try...catch for security problems.
        try {
            if (
                document.location.search.match(new RegExp('[&?]' + session_name + '=([^&?]*)'))
                || document.cookie.match(new RegExp('(?:;|^)\\s*' + session_name + '=([^;]*)'))
            ) {
                url += (url.indexOf('?') >= 0? '&' : '?') + session_name + "=" + this.escape(RegExp.$1);
            }
        } catch (e) {}
        // Store open arguments to hash.
        _openArgs = {
            method:     (method || '').toUpperCase(),
            url:        url,
            asyncFlag:  asyncFlag,
            username:   username != null? username : '',
            password:   password != null? password : ''
        }
        _ldObj = null;
        _changeReadyState(1, true); // compatibility with XMLHttpRequest
        return true;
    }}
    
    /**
     * Sends a request to a server.
     */
    t.send = function(content) {
        if (!this.readyState) {
            // send without open or after abort: no action (IE behaviour).
            return;
        }
        this._changeReadyState(1, true); // compatibility with XMLHttpRequest
        this._ldObj = null;
        
        // Prepare to build QUERY_STRING from query hash.
        var queryText = [];
        var queryElem = [];
        if (!this._hash2query(content, null, queryText, queryElem)) return;
    
        // Solve the query hashcode & return on cache hit.
        var hash = null;
        if (this.caching && !queryElem.length) {
            hash = this._openArgs.username + ':' + this._openArgs.password + '@' + this._openArgs.url + '|' + queryText + "#" + this._openArgs.method;
            var cache = JsHttpRequest.CACHE[hash];
            if (cache) {
                this._dataReady(cache[0], cache[1]);
                return false;
            }
        }
    
        // Try all the loaders.
        var loader = (this.loader || '').toLowerCase();
        if (loader && !JsHttpRequest.LOADERS[loader]) return this._error('unk_loader', loader);
        var errors = [];
        var lds = JsHttpRequest.LOADERS;
        for (var tryLoader in lds) {
            var ldr = lds[tryLoader].loader;
            if (!ldr) continue; // exclude possibly derived prototype properties from "for .. in".
            if (loader && tryLoader != loader) continue;
            // Create sending context.
            var ldObj = new ldr(this);
            JsHttpRequest.extend(ldObj, this._openArgs);
            JsHttpRequest.extend(ldObj, {
                queryText:  queryText.join('&'),
                queryElem:  queryElem,
                id:         (new Date().getTime()) + "" + JsHttpRequest.COUNT++,
                hash:       hash,
                span:       null
            });
            var error = ldObj.load();
            if (!error) {
                // Save loading script.
                this._ldObj = ldObj;
                JsHttpRequest.PENDING[ldObj.id] = this;
                return true;
            }
            if (!loader) {
                errors[errors.length] = '- ' + tryLoader.toUpperCase() + ': ' + this._l(error);
            } else {
                return this._error(error);
            }
        }
    
        // If no loader matched, generate error message.
        return tryLoader? this._error('no_loader_matched', errors.join('\n')) : this._error('no_loaders');
    }
    
    /**
     * Returns all response headers (if supported).
     */
    t.getAllResponseHeaders = function() { with (this) {
        return _ldObj && _ldObj.getAllResponseHeaders? _ldObj.getAllResponseHeaders() : [];
    }}

    /**
     * Returns one response header (if supported).
     */
    t.getResponseHeader = function(label) { with (this) {
        return _ldObj && _ldObj.getResponseHeader? _ldObj.getResponseHeader(label) : null;
    }}

    /**
     * Adds a request header to a future query.
     */
    t.setRequestHeader = function(label, value) { with (this) {
        _reqHeaders[_reqHeaders.length] = [label, value];
    }}
    
    //
    // Internal functions.
    //
    
    /**
     * Do all the work when a data is ready.
     */
    t._dataReady = function(text, js) { with (this) {
        if (caching && _ldObj) JsHttpRequest.CACHE[_ldObj.hash] = [text, js];
        responseText = responseXML = text;
        responseJS = js;
        if (js !== null) {
            status = 200;
            statusText = "OK";
        } else {
            status = 500;
            statusText = "Internal Server Error";
        }
        _changeReadyState(2);
        _changeReadyState(3);
        _changeReadyState(4);
        _cleanup();
    }}
    
    /**
     * Analog of sprintf(), but translates the first parameter by _errors.
     */
    t._l = function(args) {
        var i = 0, p = 0, msg = this._errors[args[0]];
        // Cannot use replace() with a callback, because it is incompatible with IE5.
        while ((p = msg.indexOf('%', p)) >= 0) {
            var a = args[++i] + "";
            msg = msg.substring(0, p) + a + msg.substring(p + 1, msg.length);
            p += 1 + a.length;
        }
        return msg;
    }

    /** 
     * Called on error.
     */
    t._error = function(msg) {
        msg = this._l(typeof(msg) == 'string'? arguments : msg)
        msg = "JsHttpRequest: " + msg;
        if (!window.Error) {
            // Very old browser...
            throw msg;
        } else if ((new Error(1, 'test')).description == "test") {
            // We MUST (!!!) pass 2 parameters to the Error() constructor for IE5.
            throw new Error(1, msg);
        } else {
            // Mozilla does not support two-parameter call style.
            throw new Error(msg);
        }
    }
    
    /**
     * Convert hash to QUERY_STRING.
     * If next value is scalar or hash, push it to queryText.
     * If next value is form element, push [name, element] to queryElem.
     */
    t._hash2query = function(content, prefix, queryText, queryElem) {
        if (prefix == null) prefix = "";
        if((''+typeof(content)).toLowerCase() == 'object') {
            var formAdded = false;
            if (content && content.parentNode && content.parentNode.appendChild && content.tagName && content.tagName.toUpperCase() == 'FORM') {
                content = { form: content };
            }
            for (var k in content) {
                var v = content[k];
                if (v instanceof Function) continue;
                var curPrefix = prefix? prefix + '[' + this.escape(k) + ']' : this.escape(k);
                var isFormElement = v && v.parentNode && v.parentNode.appendChild && v.tagName;
                if (isFormElement) {
                    var tn = v.tagName.toUpperCase();
                    if (tn == 'FORM') {
                        // FORM itself is passed.
                        formAdded = true;
                    } else if (tn == 'INPUT' || tn == 'TEXTAREA' || tn == 'SELECT') {
                        // This is a single form elemenent.
                    } else {
                        return this._error('inv_form_el', (v.name||''), v.tagName);
                    }
                    queryElem[queryElem.length] = { name: curPrefix, e: v };
                } else if (v instanceof Object) {
                    this._hash2query(v, curPrefix, queryText, queryElem);
                } else {
                    // We MUST skip NULL values, because there is no method
                    // to pass NULL's via GET or POST request in PHP.
                    if (v === null) continue;
                    // Convert JS boolean true and false to corresponding PHP values.
                    if (v === true) v = 1; 
                    if (v === false) v = '';
                    queryText[queryText.length] = curPrefix + "=" + this.escape('' + v);
                }
                if (formAdded && queryElem.length > 1) {
                    return this._error('must_be_single_el');
                }
            }
        } else {
            queryText[queryText.length] = content;
        }
        return true;
    }
    
    /**
     * Remove last used script element (clean memory).
     */
    t._cleanup = function() {
        var ldObj = this._ldObj;
        if (!ldObj) return;
        // Mark this loading as aborted.
        JsHttpRequest.PENDING[ldObj.id] = false;
        var span = ldObj.span;
        if (!span) return;
        // Do NOT use iframe.contentWindow.back() - it is incompatible with Opera 9!
        ldObj.span = null;
        var closure = function() {
            span.parentNode.removeChild(span);
        }
        // IE5 crashes on setTimeout(function() {...}, ...) construction! Use tmp variable.
        JsHttpRequest.setTimeout(closure, 50);
    }
    
    /**
     * Change current readyState and call trigger method.
     */
    t._changeReadyState = function(s, reset) { with (this) {
        if (reset) {
            status = statusText = responseJS = null;
            responseText = '';
        }
        readyState = s;
        if (onreadystatechange) onreadystatechange();
    }}
    
    /**
     * JS escape() does not quote '+'.
     */
    t.escape = function(s) {
        return escape(s).replace(new RegExp('\\+','g'), '%2B');
    }
}


// Global library variables.
JsHttpRequest.COUNT = 0;              // unique ID; used while loading IDs generation
JsHttpRequest.MAX_URL_LEN = 2000;     // maximum URL length
JsHttpRequest.CACHE = {};             // cached data
JsHttpRequest.PENDING = {};           // pending loadings
JsHttpRequest.LOADERS = {};           // list of supported data loaders (filled at the bottom of the file)
JsHttpRequest._dummy = function() {}; // avoid memory leaks


/**
 * These functions are dirty hacks for IE 5.0 which does not increment a
 * reference counter for an object passed via setTimeout(). So, if this 
 * object (closure function) is out of scope at the moment of timeout 
 * applying, IE 5.0 crashes. 
 */

/**
 * Timeout wrappers storage. Used to avoid zeroing of referece counts in IE 5.0.
 * Please note that you MUST write "window.setTimeout", not "setTimeout", else
 * IE 5.0 crashes again. Strange, very strange...
 */
JsHttpRequest.TIMEOUTS = { s: window.setTimeout, c: window.clearTimeout };

/**
 * Wrapper for IE5 buggy setTimeout.
 * Use this function instead of a usual setTimeout().
 */
JsHttpRequest.setTimeout = function(func, dt) {
    // Always save inside the window object before a call (for FF)!
    window.JsHttpRequest_tmp = JsHttpRequest.TIMEOUTS.s; 
    if (typeof(func) == "string") {
        id = window.JsHttpRequest_tmp(func, dt);
    } else {
        var id = null;
        var mediator = function() {
            func();
            delete JsHttpRequest.TIMEOUTS[id]; // remove circular reference
        }
        id = window.JsHttpRequest_tmp(mediator, dt);
        // Store a reference to the mediator function to the global array
        // (reference count >= 1); use timeout ID as an array key;
        JsHttpRequest.TIMEOUTS[id] = mediator;
    }
    window.JsHttpRequest_tmp = null; // no delete() in IE5 for window
    return id;
}

/**
 * Complimental wrapper for clearTimeout. 
 * Use this function instead of usual clearTimeout().
 */
JsHttpRequest.clearTimeout = function(id) {
    window.JsHttpRequest_tmp = JsHttpRequest.TIMEOUTS.c;
    delete JsHttpRequest.TIMEOUTS[id]; // remove circular reference
    var r = window.JsHttpRequest_tmp(id);
    window.JsHttpRequest_tmp = null; // no delete() in IE5 for window
    return r;
}


/**
 * Global static function.
 * Simple interface for most popular use-cases.
 * You may also pass URLs like "GET url" or "script.GET url".
 */
JsHttpRequest.query = function(url, content, onready, nocache) {
    var req = new this();
    req.caching = !nocache;
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            onready(req.responseJS, req.responseText);
        }
    }
    req.open(null, url, true);
    req.send(content);
}


/**
 * Global static function.
 * Called by server backend script on data load.
 */
JsHttpRequest.dataReady = function(d) {
    var th = this.PENDING[d.id];
    delete this.PENDING[d.id];
    if (th) {
        th._dataReady(d.text, d.js);
    } else if (th !== false) {
        throw "dataReady(): unknown pending id: " + d.id;
    }
}


// Adds all the properties of src to dest.
JsHttpRequest.extend = function(dest, src) {
    for (var k in src) dest[k] = src[k];
}

/**
 * Each loader has the following properties which must be initialized:
 * - method
 * - url
 * - asyncFlag (ignored)
 * - username
 * - password
 * - queryText (string)
 * - queryElem (array)
 * - id
 * - hash
 * - span
 */ 
 
// }}}

// {{{ script
// Loader: SCRIPT tag.
// [+] Most cross-browser. 
// [+] Supports loading from different domains.
// [-] Only GET method is supported.
// [-] No uploading support.
// [-] Backend data cannot be browser-cached.
//
JsHttpRequest.LOADERS.script = { loader: function(req) {
    JsHttpRequest.extend(req._errors, {
        script_only_get:   'Cannot use SCRIPT loader: it supports only GET method',
        script_no_form:    'Cannot use SCRIPT loader: direct form elements using and uploading are not implemented'
    })
    
    this.load = function() {
        // Move GET parameters to the URL itself.
        if (this.queryText) this.url += (this.url.indexOf('?') >= 0? '&' : '?') + this.queryText;
        this.url += (this.url.indexOf('?') >= 0? '&' : '?') + 'JsHttpRequest=' + this.id + '-' + 'script';        
        this.queryText = '';
        
        if (!this.method) this.method = 'GET';
        if (this.method !== 'GET') return ['script_only_get'];
        if (this.queryElem.length) return ['script_no_form'];
        if (this.url.length > JsHttpRequest.MAX_URL_LEN) return ['url_too_long', JsHttpRequest.MAX_URL_LEN];

        var th = this, d = document, s = null, b = d.body;
        if (!window.opera) {
            // Safari, IE, FF, Opera 7.20.
            this.span = s = d.createElement('SCRIPT');
            var closure = function() {
                s.language = 'JavaScript';
                if (s.setAttribute) s.setAttribute('src', th.url); else s.src = th.url;
                b.insertBefore(s, b.lastChild);
            }
        } else {
            // Oh shit! Damned stupid Opera 7.23 does not allow to create SCRIPT 
            // element over createElement (in HEAD or BODY section or in nested SPAN - 
            // no matter): it is created deadly, and does not response the href assignment.
            // So - always create SPAN.
            this.span = s = d.createElement('SPAN');
            s.style.display = 'none';
            b.insertBefore(s, b.lastChild);
            s.innerHTML = 'Workaround for IE.<s'+'cript></' + 'script>';
            var closure = function() {
                s = s.getElementsByTagName('SCRIPT')[0]; // get with timeout!
                s.language = 'JavaScript';
                if (s.setAttribute) s.setAttribute('src', th.url); else s.src = th.url;
            }
        }
        JsHttpRequest.setTimeout(closure, 10);
        
        // Success.
        return null;
    }
}}
// }}}