export default route => new Promise((resolve, reject) => {
    let href = window.location.href;
     href = href.split('/');

    href[href.length-1] = href[href.length-1].substring(0,href[href.length-1].indexOf('?'));


    if (Array.isArray(route)) {
        route.forEach(item => {

            if (href.indexOf(item) === -1) {
                return resolve();
            }
            return null;
        });
    } else if (href.includes(route)) {
        resolve();
    } else {
        reject();
    }
});
