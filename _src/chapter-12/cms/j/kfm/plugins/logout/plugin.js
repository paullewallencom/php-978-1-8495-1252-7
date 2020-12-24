function plugin_logout(){
  this.name='logout';
  this.title='logout'; // TODO: String
  this.mode=5;
  this.writable=2;//all files
  this.category='kfm';
  this.extensions='all';
  this.doFunction=function(files){
    kfm_logout();
  }
}
kfm_addHook(new plugin_logout());
function kfm_logout() {
  document.location='./?logout=1';
}
