;(function($,undefined){var tag2attr={a:'href',img:'src',form:'action',base:'href',script:'src',iframe:'src',link:'href'},key=["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","fragment"],aliases={"anchor":"fragment"},parser={strict:/^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,loose:/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/},querystring_parser=/(?:^|&|;)([^&=;]*)=?([^&;]*)/g,fragment_parser=/(?:^|&|;)([^&=;]*)=?([^&;]*)/g;function parseUri(url,strictMode)
{var str=decodeURI(url),res=parser[strictMode||false?"strict":"loose"].exec(str),uri={attr:{},param:{},seg:{}},i=14;while(i--)
{uri.attr[key[i]]=res[i]||"";}
uri.param['query']={};uri.param['fragment']={};uri.attr['query'].replace(querystring_parser,function($0,$1,$2){if($1)
{uri.param['query'][$1]=$2;}});uri.attr['fragment'].replace(fragment_parser,function($0,$1,$2){if($1)
{uri.param['fragment'][$1]=$2;}});uri.seg['path']=uri.attr.path.replace(/^\/+|\/+$/g,'').split('/');uri.seg['fragment']=uri.attr.fragment.replace(/^\/+|\/+$/g,'').split('/');uri.attr['base']=uri.attr.host?uri.attr.protocol+"://"+uri.attr.host+(uri.attr.port?":"+uri.attr.port:''):'';return uri;};function getAttrName(elm)
{var tn=elm.tagName;if(tn!==undefined)return tag2attr[tn.toLowerCase()];return tn;}
$.fn.url=function(strictMode)
{var url='';if(this.length)
{url=$(this).attr(getAttrName(this[0]))||'';}
return $.url({url:url,strict:strictMode});};$.url=function(opts)
{var url='',strict=false;if(typeof opts==='string')
{url=opts;}
else
{opts=opts||{};strict=opts.strict||strict;url=opts.url===undefined?window.location.toString():opts.url;}
return{data:parseUri(url,strict),attr:function(attr)
{attr=aliases[attr]||attr;return attr!==undefined?this.data.attr[attr]:this.data.attr;},param:function(param)
{return param!==undefined?this.data.param.query[param]:this.data.param.query;},fparam:function(param)
{return param!==undefined?this.data.param.fragment[param]:this.data.param.fragment;},segment:function(seg)
{if(seg===undefined)
{return this.data.seg.path;}
else
{seg=seg<0?this.data.seg.path.length+seg:seg-1;return this.data.seg.path[seg];}},fsegment:function(seg)
{if(seg===undefined)
{return this.data.seg.fragment;}
else
{seg=seg<0?this.data.seg.fragment.length+seg:seg-1;return this.data.seg.fragment[seg];}}};};})(jQuery);