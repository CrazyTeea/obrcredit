export default route => new Promise((resolve, reject) => {
    let href = window.location.href;
    href = href.replace('index.php');
    if (href.includes('%2F'))
        href = href.split('%2F');
    else href = href.split('/');
    if (Array.isArray(route)) {
        route.forEach(item => {

            if (href.indexOf(item) !== -1) {
                return resolve();
            }
            return null;
        });
    } else if (href.indexOf(route)!==-1) {
        resolve();
    } else {
        reject();
    }
});
