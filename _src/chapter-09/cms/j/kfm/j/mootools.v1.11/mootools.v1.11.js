var $extend=function(){
var args=arguments;
if(!args[1])args=[this, args[0]];
for(var property in args[1])args[0][property]=args[1][property];
return args[0];
};
var $native=function(){
for(var i=0, l=arguments.length; i < l; i++){
arguments[i].extend=function(props){
for(var prop in props){
if(!this.prototype[prop])this.prototype[prop]=props[prop];
if(!this[prop])this[prop]=$native.generic(prop);
}
};
}
};
$native.generic=function(prop){
return function(bind){
return this.prototype[prop].apply(bind, Array.prototype.slice.call(arguments, 1));
};
};
$native(Function, Array, String, Number);
function $chk(obj){
return !!(obj||obj === 0);
};
window.xpath=!!(document.evaluate);
if(window.ActiveXObject)window.ie=true;
else if(document.childNodes && !document.all && !navigator.taintEnabled)window.webkit=true;
else if(document.getBoxObjectFor != null)window.gecko=true;
Object.extend=$extend;
if(typeof HTMLElement=='undefined'){
var HTMLElement=function(){};
if(window.webkit)document.createElement("iframe");
HTMLElement.prototype=(window.webkit)? window["[[DOMElement.prototype]]"]:{};
}
HTMLElement.prototype.htmlElement=function(){};
var Class=function(properties){
var klass=function(){
return(arguments[0] !== null && this.initialize && $type(this.initialize)=='function')? this.initialize.apply(this, arguments): this;
};
$extend(klass, this);
klass.prototype=properties;
klass.constructor=Class;
return klass;
};
Class.prototype={
extend: function(properties){
var proto=new this(null);
return new Class(proto);
}
};
Array.extend({
forEach: function(fn, bind){
for(var i=0, j=this.length; i < j; i++)fn.call(bind, this[i], i, this);
},
map: function(fn, bind){
var results=[];
for(var i=0, j=this.length; i < j; i++)results[i]=fn.call(bind, this[i], i, this);
return results;
},
contains: function(item, from){
return this.indexOf(item, from)!= -1;
},
extend: function(array){
for(var i=0, j=array.length; i < j; i++)this.push(array[i]);
return this;
}
});
Array.prototype.each=Array.prototype.forEach;
Array.each=Array.forEach;
function $each(iterable, fn, bind){
if(iterable && typeof iterable.length=='number' && $type(iterable)!= 'object'){
Array.forEach(iterable, fn, bind);
} else {
for(var name in iterable)fn.call(bind||iterable, iterable[name], name);
}
};
Array.prototype.test=Array.prototype.contains;
String.extend({
test: function(regex, params){
return(($type(regex)=='string')? new RegExp(regex, params): regex).test(this);
}
});
var Garbage={
elements: [],
collect: function(el){
if(!el.$tmp){
Garbage.elements.push(el);
el.$tmp={'opacity': 1};
}
return el;
},
trash: function(elements){
for(var i=0, j=elements.length, el; i < j; i++){
if(!(el=elements[i])|| !el.$tmp)continue;
for(var p in el.$tmp)el.$tmp[p]=null;
Garbage.elements[Garbage.elements.indexOf(el)]=null;
el.htmlElement=el.$tmp=el=null;
}
}
};
