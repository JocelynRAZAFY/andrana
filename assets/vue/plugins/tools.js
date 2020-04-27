export function urlHub(topic) {
    const url = new URL('http://localhost:3000/.well-known/mercure');
    url.searchParams.append('topic', topic);
    return url
}

export function notification(h,title,message) {
    this.$notify({
        title: title,
        message: h('i', { style: 'color: teal' }, message),
        duration: 1000
    });
}

export function createUUID() {
    let dt = new Date().getTime();
    let uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        let r = (dt + Math.random()*16)%16 | 0;
        dt = Math.floor(dt/16);
        return (c=='x' ? r :(r&0x3|0x8)).toString(16);
    });
    return uuid;
}

export function getRootPath() {
    // /sales5/public
    return ''
}

